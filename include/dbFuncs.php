<?php
# load Dotenv package
require dirname( __DIR__ , 1) . '/vendor/autoload.php';

use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Dotenv\Dotenv;

$factory = new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]);

Dotenv::create(dirname( __DIR__ , 3), null, $factory)->load();

# load the .env environment file
// $dotenv = Dotenv\Dotenv::create( dirname( __DIR__ , 3) );
// $dotenv->load();

class dbFuncs
{
  // constructor 
  public function __construct()
  {
    # create a PDO connection object using the environment variables
    try {
      $db = new PDO("mysql:host=" . $_ENV["HOST"] . ";dbname=" . $_ENV["DBNAME"], $_ENV["USERNAME"], $_ENV["PASSWORD"]);
  
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $db->exec("SET CHARACTER SET utf8");
    } catch(PDOException $e) {
      die('ERROR - dbfuncs: ' . $e->getMessage());
    }
  
    # define a class level variable for the PDO connection
    $this->db = $db;

    # class variable for ProcessLog informational rows if using development server SNOWBALL
    $this->writeProcessLogInfo = (gethostname() == "SNOWBALL") ? true : false;

    # get the devideId if the js classes.clsDeviceIdCookie has written the cookie
    if (isset($_COOKIE['deviceId'])) {
      $UserId = 'deviceId: ' . $_COOKIE['deviceId'];
    }
    else {
      $UserId = "deviceId: **unknown**";
    }

    # class variable used in function ProcessLog_insert2
    $this->deviceId = $UserId;
  } # end of constructor


  // generarte a unique id string of given length and case
  public function generateUnique($length, $case)
  {
    switch (strtoupper($case)) {
      case 'U':
        return substr(str_shuffle('123456789BCDFGHJKLMNPQRSTVWXYZ987654321'), 0, $length);
        break;
      case 'L':
        return substr(str_shuffle('123456789bcdfghjkmnpqrstvwxyz987654321'), 0, $length);
      break;
      default:
        return substr(str_shuffle('123456789BCDFGHJKLMNPQRSTVWXYZ987654321bcdfghjkmnpqrstvwxyz987654321'), 0, $length);
      break;
    }
  }

  
  // get all rows from ProcessLog table with given MessType (I, E, W)
  public function ProcessLog_selectAll($MessType)
  {
    $returnArray = array('success'        =>true,
                        'ProcessLogRows'  =>null,
                        'message'         =>null);

    $sql = "select * from ProcessLog where MessType ='?' order by IdNum";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $MessType, PDO::PARAM_STR,1);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
       $returnArray['success'] = false; 
       $returnArray['message'] = "No MessType: {$MessType} rows found"; 
       $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.ProcessLog_selectAll', "No rows found", $sql);

    }
    else {
      $returnArray['message'] = "Number of MessType: {$MessType}, ProcessLog rows: {$rowCount}";
      $returnArray['ProcessLogRows'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    return $returnArray;
  } // end of function ProcessLog_selectAll


  // get max(IdNum) from ProcessLog table with MessType = 'E' or 'W' and AlarmRaised = 'N'
  public function ProcessLog_selectMaxIdNum()
  {
    $sql = "select IFNULL(max(IdNum),0) from ProcessLog where MessType<>'I' and AlarmRaised='N'";

    $maxIdNum = $this->db->query($sql)->fetchColumn();

    return $maxIdNum;
  } // end of function ProcessLog_selectMaxIdNum


  // get all rows from ProcessLog table with MessType = 'E' or 'W' and AlarmRaised = 'N' less or equal to max(Idnum)
  public function ProcessLog_selectErrorsNotReported($maxIdNum)
  {
    $returnArray = array('success'        =>true,
                        'ProcessLogRows'  =>null,
                        'message'         =>null);

    $sql = "select * from ProcessLog where MessType<>'I' and AlarmRaised='N' and IdNum<=? order by IdNum";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $maxIdNum, PDO::PARAM_INT);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
        $returnArray['success'] = false; 
        $returnArray['message'] = "No ProcessLog rows with MessType E or W found where IdNum<=: {$maxIdNum}"; 
        $this->ProcessLog_insert2('W',
                                  'ProcessLog_ajax - method: ProcessLog_reportErrors',
                                  'dbFuncs->ProcessLog_selectErrorsNotReported', 
                                  'No ProcessLog rows found',
                                  "sql: {$sql}, maxIdNum: {$maxIdNum}" );
    }
    else {
      $returnArray['message'] = "ProcessLog: {$rowCount} rows of MessType E or W less than or equal to IdNum: {$maxIdNum}";
      $returnArray['ProcessLogRows'] = $sth->fetchAll(PDO::FETCH_NUM);
    }

    return $returnArray;
  } // end of function ProcessLog_selectErrorsNotReported
  

  // update AlarmRaised to 'Y' for ProcessLog rows with MessType = 'E' or 'W' and AlarmRaised = 'N' less or equal to max(Idnum)
  public function ProcessLog_updateErrorsNotReported($maxIdNum)
  {
    $returnArray = array( 'success' =>true,
                          'message' =>null);

    $sql = "call spProcessLog_updAlarmRaised(?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $maxIdNum, PDO::PARAM_INT);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
        $returnArray['success'] = false; 
        $returnArray['message'] = "No ProcessLog rows with MessType E or W with IdNum<=: {$maxIdNum} were updated"; 
        $this->ProcessLog_insert2('E',
                                  'ProcessLog_ajax - method: ProcessLog_reportErrors',
                                  'dbFuncs->ProcessLog_updateErrorsNotReported', 
                                  $returnArray['message'],
                                  "sql: {$sql}, maxIdNum: {$maxIdNum}" );
    }
    else {
      $returnArray['message'] = "ProcessLog: {$rowCount} rows of MessType E or W <= to IdNum: {$maxIdNum} were updated";
      if ($writeProcessLogInfo) {
        $this->ProcessLog_insert2('I',
                                  'ProcessLog_ajax - method: ProcessLog_reportErrors',
                                  'dbFuncs->ProcessLog_updateErrorsNotReported', 
                                  $returnArray['message'],
                                  "sql: {$sql}, maxIdNum: {$maxIdNum}" );
      }

    }

    return $returnArray;
  } // end of function ProcessLog_updateErrorsNotReported
  

  # Function to insert a row into ProcessLog table
  public function ProcessLog_insert(
                              $MessType,
                              $Application,
                              $Routine,
                              $UserId,
                              $ErrorMess,
                              $Remarks
  )
  {
    $q = "call spProcessLog_Insert( '$MessType',
                                    '$Application',
                                    '$Routine',
                                    '$UserId',
                                    '$ErrorMess',
                                    '$Remarks'
                                    )";

    $this->db->exec ( $q );
    return;
  }


  # Function to insert a row into ProcessLog table with userId = $dbFuncs->deviceId
  public function ProcessLog_insert2(
        $MessType,
        $Application,
        $Routine,
        $ErrorMess,
        $Remarks
  )
  {
    $sql = "call spProcessLog_Insert(?, ?,  ?,  ?,  ?,  ? )";
    $stmt = $this->db->prepare($sql);
    # , array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false)
    $stmt->bindParam(1, $MessType,        PDO::PARAM_STR,1);
    $stmt->bindParam(2, $Application,     PDO::PARAM_STR,100);
    $stmt->bindParam(3, $Routine,         PDO::PARAM_STR,100);
    $stmt->bindParam(4, $this->deviceId,  PDO::PARAM_STR,100);  // UserId column
    $stmt->bindParam(5, $ErrorMess,       PDO::PARAM_STR,2000);
    $stmt->bindParam(6, $Remarks,         PDO::PARAM_STR,2000);
    $stmt->execute();

    return;
  }


  // retrieve a single CottageWeek row
  public function cottageWeek_get($dateSat, $cottageNum)
  {
    $returnArray = array('success'  => true,
                        'data'      => null,
                        'message'   => null);
    
    $sql = "select RentDay, RentWeek, bShortBreaksAllowed from CottageWeek where DateSat=? and CottageNum=?;";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(1, $dateSat,    PDO::PARAM_STR);
    $stmt->bindParam(2, $cottageNum, PDO::PARAM_INT);

    $stmt->execute();
    $returnArray['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $returnArray;
  }


  // select all cottage week from table CottageWeek for given cottageNum after given date
  public function cottageWeek_selectAll($dateSat, $cottageNum)
  {
    $returnArray = array( 'success'         => true,
                          'cottageWeekRows' => null,
                          'message'         => null);

    $sql='select * from CottageWeek where DateSat>=? and CottageNum=?;';

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(1, $dateSat,    PDO::PARAM_STR);
    $stmt->bindParam(2, $cottageNum, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if ($rowCount==0) {
      $returnArray['success'] = false; 
      $returnArray['message'] = "No CottageWeek rows for cottage num [{$cottageNum}] after {$dateSat}"; 
      $this->ProcessLog_insert2('E',
                                'bookingView_ajax - method: cottageWeek_selectAll',
                                'dbFuncs->cottageWeek_selectAll', 
                                $returnArray['message'],
                                null );
    } else {
      $returnArray['cottageWeekRows'] = $stmt->fetchAll(PDO::FETCH_ASSOC); 
      $returnArray['message'] = "{$rowCount} CottageWeek rows for cottage num [{$cottageNum}] after {$dateSat}"; 
      if ($this->writeProcessLogInfo) {
        $this->ProcessLog_insert2('I',
                                  'bookingView_ajax - method: cottageWeek_selectAll',
                                  'dbFuncs->cottageWeek_selectAll', 
                                  null,
                                  $returnArray['message']
        );
      }
    }
    return $returnArray;
  }
  
  
  // select cottage rents by week from table CottageWeek for page newBooking.php
  public function newBooking_crosstab($startDate, $yearEnd)
  {
    // set up the return array
    $returnArray = array('success'            =>true,
                        'cottageWeekRows'     =>null,
                        'cottageBookRows'     =>null,
                        'cottageBookAllRows'  =>null,
                        'errorMess'           =>null
    );

    // use groupby DateSat to form rows with the cottage name in the columns
    $sql='select 	DS.datesat, 
                  DS.bShortBreaksAllowed,
                  CornflowerD,
                  CornflowerW,
                  CowslipD,
                  CowslipW,
                  MeadowsweetD,
                  MeadowsweetW
          from DateSat as DS
          left outer join 
            (select DateSat,
                sum(if(CottageNum=1,RentDay,  0)) 	as CornflowerD,
                sum(if(CottageNum=1,RentWeek, 0))	  as CornflowerW,
                sum(if(CottageNum=2,RentDay,  0)) 	as CowslipD,
                sum(if(CottageNum=2,RentWeek, 0)) 	as CowslipW,
                sum(if(CottageNum=3,RentDay,  0)) 	as MeadowsweetD,
                sum(if(CottageNum=3,RentWeek, 0)) 	as MeadowsweetW
                from CottageWeek
                group by DateSat
            ) as CW on DS.dateSat = CW.DateSat
              where DS.dateSat between ? and ?
              order by 1;
        ';

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(1, $startDate, PDO::PARAM_STR);
      $stmt->bindParam(2, $yearEnd,   PDO::PARAM_STR);
      $stmt->execute();
      $returnArray['cottageWeekRows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if($returnArray['cottageWeekRows'] ) {
        // if rows returned then get the confirmed booking rows summed by number of days as a cross tab
        $sql='select DateSat,
                    sum(if(CottageNum=1,DATEDIFF(LastNight,FirstNight)+1, 0)) 	as CornflowerNights,
                    sum(if(CottageNum=2,DATEDIFF(LastNight,FirstNight)+1, 0)) 	as CowslipNights,
                    sum(if(CottageNum=3,DATEDIFF(LastNight,FirstNight)+1, 0)) 	as MeadowsweetNights
                    from CottageBook
              where DateSat between ? and ?
              and BookingStatus="C"
              group by DateSat
              order by 1;
            ';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $startDate, PDO::PARAM_STR);
        $stmt->bindParam(2, $yearEnd,   PDO::PARAM_STR);
        $stmt->execute();
        $returnArray['cottageBookRows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get all the individual confirmed bookings for use in the modal popup for a selected week
        $sql = 'select DateSat, 
                  CottageNum, 
                  FirstNight, 
                  LastNight, 
                  DATEDIFF(lastnight,firstnight)+1 as numNights
                from CottageBook
                where DateSat between ? and ?
                and BookingStatus="C"
                order by 1,2;';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $startDate, PDO::PARAM_STR);
        $stmt->bindParam(2, $yearEnd,   PDO::PARAM_STR);
        $stmt->execute();
        $returnArray['cottageBookAllRows'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      else {
        $returnArray['success']   = false;
        $returnArray['errorMess'] = 'no rows returned';
      }

      return $returnArray;
  } // end of newBooking_crosstab


  // select cottage rents and books from tables CottageWeek and CottageBook
  public function cottageWeekBook_select($dateSat)
  {
    // use groupby DateSat to form rows with the cottage name in the columns
    $sql='select CW.DateSat,
            sum(if(CW.CottageNum=1,RentDay,0)) as CornflowerD,
            sum(if(CW.CottageNum=1,RentWeek,0)) as CornflowerW,
            sum(if(CW.CottageNum=2,RentDay,0)) as CowslipD,
            sum(if(CW.CottageNum=2,RentWeek,0)) as CowslipW,
            sum(if(CW.CottageNum=3,RentDay,0)) as MeadowsweetD,
            sum(if(CW.CottageNum=3,RentWeek,0)) as MeadowsweetW,
            max(if(CB.CottageNum=1,true,0)) as Cottage1_booked,
            max(if(CB.CottageNum=2,true,0)) as Cottage2_booked,
            max(if(CB.CottageNum=3,true,0)) as Cottage3_booked
          from CottageWeek as CW
          where CW.DateSat >= ' . '\'' . $dateSat . '\'' . '
          left outer join CottageBook as CB on CW.DateSat = CB.DateSat
          group by CW.DateSat
          order by 1;';

    try {
      $sth = $this->db->prepare($sql);
      $sth->execute();
      return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2',' dbFuncs.cottageWeekBook_select', $e->getMessage(), $sql);
      return 'ERROR - ' . $e->getMessage();
    }
  }


  public function rentWeek_select($dateSat)
  {
    // use groupby DateSat to form rows with the cottage name in the columns
    $sql='select DateSat,
            sum(if(CottageNum=1,Rent,0)) as Cornflower,
            sum(if(CottageNum=2,Rent,0)) as Cowslip,
            sum(if(CottageNum=3,Rent,0)) as Meadowsweet
          from RentWeek
          where DateSat >= ' . '\'' . $dateSat . '\'' . '
          group by DateSat
          order by 1;';

    try {
      $sth = $this->db->prepare($sql);
      $sth->execute();
      return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.rentWeek_select', $e->getMessage(), $sql);
      return 'ERROR - ' . $e->getMessage();
    }
  }


  // inserts or replaces rows in table CottageWeek
  public function cottageWeek_replace( $DateSat,
                                    $CottageNum,
                                    $RentDay,
                                    $RentWeek	
  )
  {
    $q = "call spCottageWeek_replace( '$DateSat',
                                      $CottageNum,
                                      $RentDay,
                                      $RentWeek)"	;
    try {
      $sth = $this->db->prepare($q);
      $sth->execute();
      return;
    }
    catch (Exception $e) {
      $this->ProcessLog_insert('E', 'MGF2', 'dbFuncs.cottageWeek_replace', 'me', $e->getMessage(), $q);
      return 'ERROR - ' . $e->getMessage();
    }
  }


  // inserts or replaces rows in table rentWeek
  public function rentWeek_replace( $DateSat,
                                    $WeekNum,
                                    $CottageNum,
                                    $Rent	
  )
  {
    $q = "call spRentWeek_replace ( $DateSat,
                                    $WeekNum,
                                    $CottageNum,
                                    $Rent)"	;
    try {
      $this->db->exec ( $q );
      return;
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.rentWeek_replace', $e->getMessage(), $q);
      return 'ERROR - ' . $e->getMessage();
    }
  } 


  // return a list of dateSat rows from table DatSat
  public function dateSat_select( $DateSat )
  {
    $sql = "select DateSat, bShortBreaksAllowed from DateSat where DateSat >=? order by 1;"	;
    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $DateSat, PDO::PARAM_STR);
      $sth->execute();
      return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.dateSat_select', $e->getMessage(), $sql);
      return 'ERROR - ' . $e->getMessage();
    }
  }


  // return rows from CottageBook for given DateSat and CottageNum
  public function CottageBook_select( $DateSat, $CottageNum)
  {      
    // set up the return array
    $returnArray = array('success'          =>true,
                        'cottageBookCount'  =>0,
                        'cottageBookRows'   =>null,
                        'errorMess'         =>null
                      );

    $sql = "select *,datediff(lastnight,firstnight)+1 as numNights from CottageBook where DateSat =? and CottageNum =? order by FirstNight;";
    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $DateSat,    PDO::PARAM_STR);
      $sth->bindParam(2, $CottageNum, PDO::PARAM_INT);

      $sth->execute();
      $returnArray['cottageBookCount'] = $sth->rowCount();
      $returnArray['errorMess'] = ($returnArray['cottageBookCount']==0) ? 'No bookings yet' : '' ;
      $returnArray['cottageBookRows'] = $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $returnArray['success'] = false;
      $returnArray['errorMess'] = $e->getMessage();
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_select', $returnArray['errorMess'], $sql);
    }
    finally {
      return $returnArray;
    }
  } // end of function CottageBook_select


  // return all rows from CottageBook for given CottageNum with dateSat >= given date
  public function CottageBook_selectAll( $DateSat, $CottageNum)
  {      
    // set up the return array
    $returnArray = array( 'success'           =>true,
                          'cottageBookRows'   =>null,
                          'message'           =>null
                      );

    $sql =  "select *, "
            . "datediff(lastnight,firstnight)+1 as numNights "
            . "from CottageBook where DateSat >=? and CottageNum =? order by DateSat;";
            
    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $DateSat,    PDO::PARAM_STR);
    $sth->bindParam(2, $CottageNum, PDO::PARAM_INT);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount==0) {
      $returnArray['success'] = false; 
      $returnArray['message'] = "No CottageBook rows for cottage num [{$cottageNum}] after {$dateSat}"; 
      $this->ProcessLog_insert2('E',
                                'bookingView_ajax - method: cottageBook_selectAll',
                                'dbFuncs->cottageBook_selectAll', 
                                $returnArray['message'],
                                null );
    } else {
      $returnArray['cottageBookRows'] = $sth->fetchAll(PDO::FETCH_ASSOC); 
      $returnArray['message'] = "{$rowCount} CottageBook rows for cottage num [{$CottageNum}] after {$DateSat}"; 

      if ($this->writeProcessLogInfo) {
        $this->ProcessLog_insert2('I',
                                  'bookingView_ajax - method: cottageBook_selectAll',
                                  'dbFuncs->cottageBook_selectAll', 
                                  null,
                                  $returnArray['message']
        );
      }
    }
    return $returnArray;
  }
  

  // delete a CottageBook row by IdNum
  function CottageBook_delete( $IdNum )
  {     
    // set up the return array
    $returnArray = array('success'  =>true,
                        'message'   =>null);

    if (empty($IdNum)) {
      $returnArray['success'] = false;
      $returnArray['message'] = '$IdNum is empty';
      return $returnArray;
    }                    

    $sql = "call spCottageBook_delete(?)"; 
    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $IdNum, PDO::PARAM_INT);
    $sth->execute();
    $count = $sth->rowCount();
    $returnArray['message'] = "$count booking deleted";

    # stored procedure should return a count of 1
    if( !$count===1 ) {
      $returnArray['success'] = false;
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_delete','Deletion failed', 
                                $sql . ' IdNum: ' . $IdNum . ' ' . $returnArray['message']
                              );
    }
    return $returnArray;
  } // end of function CottageBook_delete


  // check proposed dates for a new booking against existing bookings in the database. returns number of clashes
  function CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight)
  {
    # set up the return array
    $returnArray = array('success'    => true,
                        'clashCount'  => 0,
                        'errorMess'   => null);

    $sql = "call spCottageBook_check(?,?,?,?)";

    $sth = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
    $sth->bindParam(1, $DateSat,      PDO::PARAM_STR);
    $sth->bindParam(2, $CottageNum,   PDO::PARAM_INT,1);
    $sth->bindParam(3, $FirstNight,   PDO::PARAM_STR);
    $sth->bindParam(4, $LastNight,    PDO::PARAM_STR);

    $sth->execute();
    $returnArray['clashCount'] = $sth->fetchColumn();
    
    return $returnArray;
  } // end of function CottageBook_check


  // insert a new CottageBook row
  function CottageBook_insert( $DateSat, $CottageNum, $FirstNight, $LastNight, $Rental, $Notes, $BookingRef, $BookingStatus )
  { 
    $returnArray = array('success'      =>true,
                        'insertedIdNum' =>0,
                        'errorMess'     =>null);

    $sql = "call spCottageBook_Insert(?,?,?,?,?,?,?,?)";

    $sth = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
    $sth->bindParam(1, $DateSat,        PDO::PARAM_STR);
    $sth->bindParam(2, $CottageNum,     PDO::PARAM_INT,1);
    $sth->bindParam(3, $FirstNight,     PDO::PARAM_STR);
    $sth->bindParam(4, $LastNight,      PDO::PARAM_STR);
    $sth->bindParam(5, $BookingStatus,  PDO::PARAM_STR,1);
    $sth->bindParam(6, $BookingRef,     PDO::PARAM_STR,4);
    $sth->bindParam(7, $Rental,         PDO::PARAM_INT);
    $sth->bindParam(8, $Notes,          PDO::PARAM_STR);
    $sth->execute();

    $returnArray['insertedIdNum'] = $sth->fetchColumn();

    // check for SQL id returning 0
    if($returnArray['insertedIdNum'] == 0 ) {
      $this->ProcessLog_insert2('E','MGF2','dbFuncs.spCottageBook_Insert','Insert failed', $sql);
      $returnArray['success'] = false;
      $returnArray['errorMess'] = 'ERROR - Insert failed; $returnArray[insertedIdNum]: ' . $returnArray['insertedIdNum'] ;
    }

    return $returnArray;
  } // end of function CottageBook_insert
  

  // update the BookingStatus column in CottageBook table
  function CottageBook_updStatus ($IdNum, $BookingStatus) {

    $returnArray = array('success'    => true,
                        'message'     => "CottageBook status updated");

    if (empty($IdNum) || empty($BookingStatus)) {
      $returnArray['success'] = false;
      $errMess = "One or both of the 2 parameters are empty. IdNum: [$IdNum] BookingStatus: [$BookingStatus]";
      $returnArray['message'] = $errMess;
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updStatus', $errMess, null);
      return $returnArray; 
    }

    $sql = "call spCottageBook_updStatus(?,?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $IdNum,          PDO::PARAM_INT);
    $sth->bindParam(2, $BookingStatus,  PDO::PARAM_STR,1);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if (!$rowCount == 1) {
      $returnArray['success'] = false;
      $errMess = "rowCount: $rowCount should be 1. IdNum: $IdNum BookingStatus: $BookingStatus";
      $returnArray['message'] = $errMess;

      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updStatus', $errMess, $sql);
    }
    
    return $returnArray; 
  } // end of function CottageBook_updStatus

  
  // update the BookingNotes column in CottageBook table
  function CottageBook_updNotes ($IdNum, $Notes) {

    $returnArray = array('success'    => true,
                        'message'     => "CottageBook notes updated");

    $sql = "call spCottageBook_updNotes(?,?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $IdNum,  PDO::PARAM_INT);
    $sth->bindParam(2, $Notes,  PDO::PARAM_STR,100);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if (!$rowCount == 1) {
      $returnArray['success'] = false;
      $errMess = "rowCount: $rowCount should be 1. IdNum: $IdNum Notes: $Notes";
      $returnArray['message'] = $errMess;

      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updNotes', $errMess, $sql);
    }
    
    return $returnArray; 
  } // end of function CottageBook_updNotes

  // CottageBook_upd
  function CottageBook_upd($IdNum, $BookingStatus, $Rental, $Notes, $BookingSource, $ExternalReference, $ContactEmail) {

    $returnArray = array('success'    => true,
                        'message'     => "CottageBook updated");

    $sql = "call spCottageBook_upd(?,?,?,?,?,?,?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $IdNum,              PDO::PARAM_INT);
    $sth->bindParam(2, $BookingStatus,      PDO::PARAM_STR,1);
    $sth->bindParam(3, $Rental);
    $sth->bindParam(4, $Notes,              PDO::PARAM_STR,100);
    $sth->bindParam(5, $BookingSource,      PDO::PARAM_STR,1);
    $sth->bindParam(6, $ExternalReference,  PDO::PARAM_STR,20);
    $sth->bindParam(7, $ContactEmail,       PDO::PARAM_STR,50);

    $sth->execute();
    $rowCount = $sth->rowCount();
    if (!$rowCount == 1) {
      $returnArray['success'] = false;
      $errMess = "rowCount: $rowCount should be 1. IdNum: $IdNum";
      $returnArray['message'] = $errMess;

      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_upd', $errMess, $sql);
    }
    
    return $returnArray; 
  } # end of function CottageBook_upd


  // insert a row in DeviceId table
  function DeviceId_insert($deviceId, $userAgentString)
  {
    $returnArray = array('success'      => true,
                         'rowInserted'  => false,
                         'message'      => null);

    $sql = "call spDeviceId_insert(?,?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $deviceId,  PDO::PARAM_STR,20);
    $sth->bindParam(2, $userAgentString, PDO::PARAM_STR,250);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 1) { $returnArray['rowInserted'] = true; }

    return $returnArray; 
  } // end of function DeviceId_insert


  // select all DeviceId table rows
  function DeviceId_selectAll()
  {
    $returnArray = array('success'      =>true,
                        'DeviceIdRows'  =>false,
                        'message'       =>null);

    $sql = "select * from DeviceId order by UserAgentString";

    $sth = $this->db->prepare($sql);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
       $returnArray['success'] = false; 
       $returnArray['message'] = "no rows found"; 
       $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.DeviceId_selectAll', "No rows found", $sql);

    }
    else {
      $returnArray['DeviceIdRows'] = $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    return $returnArray;
  } // end of function DeviceId_selectAll


  // update DeviceDesc for given DeviceId row
  function DeviceId_updDeviceDesc($DeviceId, $DeviceDesc)
  {
    $returnArray = array('success'    => true,
                        'rowUpdated'  => false,
                        'message'     => null);

    $sql = "call spDeviceId_updDeviceDesc(?,?)";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $DeviceId,   PDO::PARAM_STR,20);
    $sth->bindParam(2, $DeviceDesc, PDO::PARAM_STR,50);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
       $returnArray['success'] = false; 
       $returnArray['message'] = "row not updated"; 
       $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.DeviceId_updDeviceDesc', "Row not updated",
       "$DeviceId: " . $DeviceId . " $DeviceDesc: " . $DeviceDesc);
    }
    else {
      $returnArray['rowUpdated'] = true;
    }

    return $returnArray;
  } // end of function DeviceId_updDeviceDesc


  // Delete a given DeviceId row
  function DeviceId_delete($DeviceId)
  {
    $returnArray = array('success'      =>true,
                        'rowDeleted'    =>false,
                        'message'       =>null);

    $sql = "delete from DeviceId where DeviceId = ?";

    $sth = $this->db->prepare($sql);
    $sth->bindParam(1, $DeviceId,   PDO::PARAM_STR,20);
    $sth->execute();
    $rowCount = $sth->rowCount();
    if ($rowCount == 0) {
       $returnArray['success'] = false; 
       $returnArray['message'] = "row not deleted"; 
       $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.DeviceId_delete', "Row not deleted",
       "$DeviceId: " . $DeviceId);

    }
    else {
      $returnArray['rowDeleted'] = true;
    }

    return $returnArray;
  } // end of function DeviceId_delete


} // end of class dbFuncs

$dbFuncs = new dbFuncs(); // instantiate the class as $dbFuncs

?>
