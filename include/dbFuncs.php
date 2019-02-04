<?php

include('dbFuncs0.php');

class dbFuncs
{
  // constructor using the PDO connection variable $db from dbFuncs0.php
  public function __construct(PDO $db)
  {
    $this->db = $db;

    // get the devideId if the js classes.clsDeviceIdCookie has written the cookie
    if (isset($_COOKIE['deviceId'])) {
      $UserId = 'deviceId: ' . $_COOKIE['deviceId'];
    }
    else {
      $UserId = "deviceId: **unknown**";
    }

    // used in function ProcessLog_insert2
    $this->deviceId = $UserId;
  }


  // generarte a unique id string of given length and case
  Public function generateUnique($length, $case)
  {
    // $randomString = substr(str_shuffle(md5(time())), 0, $length);
    switch (strtoupper($case)) {
      case 'U':
        return substr(str_shuffle('123456789BCDFGHJKLMNPQRSTVWXYZ987654321'), 0, $length);
        break;
      case 'L':
        return substr(str_shuffle('123456789bcdfghjkmnpqrstvwxyz987654321'), 0, $length);
      break;
      default:
        return substr(str_shuffle('123456789BCDFGHJKLMNPQRSTVWXYZ987654321bcdfghjkmnpqrstvwxyz987654321'), 0,$length);
      break;
    }
  }


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

    /*  using Prepare
          $stmt = $this->conn->prepare($q);
          $var = $stmt->execute([1]);
    */

    try {
          $this->db->exec ( $q );
          return;
        }
    catch (Exception $e) {
      echo 'ERROR - ' . $e->getMessage();
      }
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
    $sql = "call spProcessLog_Insert(?,
                                     ?,
                                     ?,
                                     ?,
                                     ?,
                                     ?
                                     )";
    $stmt = $this->db->prepare($sql);
    # , array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false)
    $stmt->bindParam(1, $MessType,          PDO::PARAM_STR,1);
    $stmt->bindParam(2, $Application,       PDO::PARAM_STR,100);
    $stmt->bindParam(3, $Routine,           PDO::PARAM_STR,100);
    $stmt->bindParam(4, $this->deviceId,    PDO::PARAM_STR,100);  // UserId column
    $stmt->bindParam(5, $ErrorMess,         PDO::PARAM_STR,2000);
    $stmt->bindParam(6, $Remarks,           PDO::PARAM_STR,2000);
    $stmt->execute();
  }


  // retrieve a single CottageWeek row
  public function cottageWeek_get($dateSat, $cottageNum)
  {
    $returnArray = array('success' =>true,
                        'data'     =>null);
    
    $sql = "select RentDay, RentWeek, bShortBreaksAllowed from CottageWeek where DateSat=? and CottageNum=?;";

    try {
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(1, $dateSat,    PDO::PARAM_STR);
      $stmt->bindParam(2, $cottageNum, PDO::PARAM_INT);

      $stmt->execute();
      $returnArray['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $returnArray['success'] = false;
      $returnArray['data'] = $e->getMessage();
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.cottageWeek_get', $e->getMessage(), $sql);
    }
    finally{return $returnArray;}
  }


  // select all cottage week from table CottageWeek for given cottageNum after given date
  public function cottageWeek_selectAll($dateSat, $cottageNum)
  {
    $sql='select * from CottageWeek where DateSat>=? and CottageNum=?;';

    try {
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(1, $dateSat,    PDO::PARAM_STR);
      $stmt->bindParam(2, $cottageNum, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.cottageWeek_selectAll', $e->getMessage(), $sql);
      return 'ERROR - ' . $e->getMessage();
    }
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
        $sql = 'select DateSat, CottageNum, FirstNight, LastNight, (lastnight-firstnight)+1 as numNights
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
    $sql =  "select *, "
            . "datediff(lastnight,firstnight)+1 as numNights "
            . "from CottageBook where DateSat >=? and CottageNum =? order by DateSat;";
            
    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $DateSat,    PDO::PARAM_STR);
      $sth->bindParam(2, $CottageNum, PDO::PARAM_INT);

      $sth->execute();
      return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_selectAll', $e->getMessage(), $sql);      
      return 'ERROR - ' . $e->getMessage();
    }
  }
  

  // delete a CottageBook row by IdNum
  function CottageBook_delete( $IdNum )
  {     
    // set up the return array
    $returnArray = array('success'    =>true,
                        'errorMess'   =>null);

    $sql = "call spCottageBook_delete(?)"; 
    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $IdNum, PDO::PARAM_INT);
      $sth->execute();
      $count = $sth->rowCount();
      if( !$count===1 ) {
        $returnArray['success'] = false;
        $returnArray['errorMess'] = 'Records deleted count: ' . $count . ', should be 1';
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_delete','Deletion failed', $sql . ' IdNum: ' . $IdNum . ' ' . $returnArray['errorMess']);
      }
    }
    catch (Exception $e) {
      $returnArray['success']   = false;
      $returnArray['errorMess'] = $e->getMessage();
      $this->ProcessLog_insert2('E','MGF2','dbFuncs.CottageBook_delete', $e->getMessage(), $sql. ' IdNum: ' . $IdNum);
    }
    finally{return $returnArray;}
  } // end of function CottageBook_delete


  // check proposed dates for a new booking against existing bookings in the database. returns number of clashes
  function CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight)
  {
    // set up the return array
    $returnArray = array('success'    =>true,
                        'clashCount'  =>0,
                        'errorMess'   =>null);

    $sql = "call spCottageBook_check(?,?,?,?)";

    try {
      $sth = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
      $sth->bindParam(1, $DateSat,      PDO::PARAM_STR);
      $sth->bindParam(2, $CottageNum,   PDO::PARAM_INT);
      $sth->bindParam(3, $FirstNight,   PDO::PARAM_STR);
      $sth->bindParam(4, $LastNight,    PDO::PARAM_STR);

      $sth->execute();
      $returnArray['clashCount'] = $sth->fetchColumn();
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_Insert', $e->getMessage(), $sql);
      $returnArray['success'] = false;
      $returnArray['errorMess'] = $e->getMessage();
    }
    finally {
      return $returnArray;
    }
  } // end of function CottageBook_check


  // insert a new CottageBook row
  function CottageBook_insert( $DateSat, $CottageNum, $FirstNight, $LastNight, $Rental, $Notes, $BookingRef, $BookingStatus )
  { 
    $returnArray = array('success'      =>true,
                        'insertedIdNum' =>0,
                        'errorMess'     =>null);

    $sql = "call spCottageBook_Insert(?,?,?,?,?,?,?,?)";

    try {
      $sth = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
      $sth->bindParam(1, $DateSat,        PDO::PARAM_STR);
      $sth->bindParam(2, $CottageNum,     PDO::PARAM_INT);
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
        $returnArray['errorMess'] = 'ERROR - Insert failed; $returnArray[insertedIdNum] :' . $returnArray['insertedIdNum'] ;
      }
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_Insert', $e->getMessage(), $sql);
      $returnArray['success'] = false;
      $returnArray['errorMess'] = 'ERROR - ' . $e->getMessage();
    }
    finally{ return $returnArray; }
  } // end of function CottageBook_insert
  

  // update the BookingStatus column in CottageBook table
  function CottageBook_updStatus ($IdNum, $BookingStatus) {

    $returnArray = array('success'      =>true,
                        'errorMess'     =>null);

    $sql = "call spCottageBook_updStatus(?,?)";

    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $IdNum,          PDO::PARAM_INT);
      $sth->bindParam(2, $BookingStatus,  PDO::PARAM_STR,1);
      $sth->execute();
      $rowCount = $sth->rowCount();
      if (!$rowCount == 1) {
        $returnArray['success'] = false;
        $returnArray['errorMess'] = 'ERROR - Contact Support';

        $errMess =   '$rowCount: ' . $rowCount . ' should be 1.' .
          '$IdNum: ' . $IdNum .
          ' $BookingStatus: ' . $BookingStatus;
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updStatus', 
          $errMess, 
          $sql);
      }
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updStatus', $e->getMessage(), $sql);
      $returnArray['success']   = false;
      $returnArray['errorMess'] = $e->getMessage();
    }
    finally{ return $returnArray; }
  } // end of function CottageBook_updStatus


  // create or update a row in DeviceId table
  function DeviceId_insert($deviceId, $userAgentString)
  {
    $returnArray = array('success'      =>true,
                         'rowInserted'  =>false,
                         'message'      =>null);

    $sql = "call spDeviceId_insert(?,?)";

    try {
      $sth = $this->db->prepare($sql);
      $sth->bindParam(1, $deviceId,        PDO::PARAM_STR,20);
      $sth->bindParam(2, $userAgentString, PDO::PARAM_STR,250);
      $sth->execute();
      $rowCount = $sth->rowCount();
      if ($rowCount == 1) { $returnArray['rowInserted'] = true; }
    }
    catch (Exception $e) {
      $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.DeviceId_insert', $e->getMessage(),
        "$deviceId: " . $deviceId . " userAgentString: " . $userAgentString);

      $returnArray['success'] = false;
      $returnArray['message'] = $e->getMessage();
    }
    finally{ return $returnArray; }
  } // end of function DeviceId_upsert


} // end of class dbFuncs

$dbFuncs = new dbFuncs($db); // instantiate the class as $dbFuncs ising the PDO connection object $db

?>
