<?php

include('dbFuncs0.php') ;

class dbFuncs
{

    public function __construct(PDO $db)
    {
      $this->db = $db;
    }


    // generarte a unique id string
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
      catch (PDOException $e) {
        echo 'ERROR - ' . $e->getMessage();
        }
    }


     # Function to insert a row into ProcessLog table without userId
    public function ProcessLog_insert2(
          $MessType,
          $Application,
          $Routine,
          $ErrorMess,
          $Remarks
    )
    {

      $sql = "call spProcessLog_Insert2(?,
                                        ?,
                                        ?,
                                        ?,
                                        ?
                                        )";
      $stmt = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
      $stmt->bindParam(1, $MessType,    PDO::PARAM_STR);
      $stmt->bindParam(2, $Application, PDO::PARAM_STR);
      $stmt->bindParam(3, $Routine,     PDO::PARAM_STR);
      $stmt->bindParam(4, $ErrorMess,   PDO::PARAM_STR);
      $stmt->bindParam(5, $Remarks,     PDO::PARAM_STR);
      $stmt->execute();
    }


    // retrieve a single CottageWeek row
    public function cottageWeek_get( $dateSat, $cottageNum)
    {
      $sql = "select RentDay, RentWeek, bShortBreaksAllowed from CottageWeek where DateSat=? and CottageNum=?;";

      try {
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $dateSat,    PDO::PARAM_STR);
        $stmt->bindParam(2, $cottageNum, PDO::PARAM_INT);
  
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
      }
      catch (Exception $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.cottageWeek_get', $e->getMessage(), $sql);
        throw $e;
      }
    }

    
    // select cottage rents by week from table CottageWeek
    public function cottageWeek_select($dateSat)
    {
      // use groupby DateSat to form rows with the cottage name in the columns
      $sql='select DateSat,
              sum(if(CottageNum=1,RentDay,0)) as CornflowerD,
              sum(if(CottageNum=1,RentWeek,0)) as CornflowerW,
              sum(if(CottageNum=2,RentDay,0)) as CowslipD,
              sum(if(CottageNum=2,RentWeek,0)) as CowslipW,
              sum(if(CottageNum=3,RentDay,0)) as MeadowsweetD,
              sum(if(CottageNum=3,RentWeek,0)) as MeadowsweetW
            from CottageWeek
            where DateSat >= ' . '\'' . $dateSat . '\'' . '
            group by DateSat
            order by 1;';

      try {
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
      }
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.cottageWeek_select', $e->getMessage(), $sql);
        return 'ERROR - ' . $e->getMessage();
      }
    }


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
      catch (PDOException $e) {
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
      catch (PDOException $e) {
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
      catch (PDOException $e) {
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
      $q = "call spRentWeek_replace ( '$DateSat',
                                      $WeekNum,
                                      $CottageNum,
                                      $Rent)"	;
      try {
        $this->db->exec ( $q );
        return;
      }
      catch (PDOException $e) {
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
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.dateSat_select', $e->getMessage(), $sql);
        return 'ERROR - ' . $e->getMessage();
      }
    }


    // return rows from CottageBook for given DateSat and CottageNum
    public function CottageBook_select( $DateSat, $CottageNum)
    {      
      $sql = "select *,datediff(lastnight,firstnight)+1 as numNights from CottageBook where DateSat =? and CottageNum =? order by FirstNight;";
      try {
        $sth = $this->db->prepare($sql);
        $sth->bindParam(1, $DateSat,    PDO::PARAM_STR);
        $sth->bindParam(2, $CottageNum, PDO::PARAM_INT);
  
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
      }
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_select', $e->getMessage(), $sql);
        return 'ERROR - ' . $e->getMessage();
      }
    }


    // delete a CottageBook row by IdNum
    function CottageBook_delete( $IdNum )
    {      
      $sql = "call spCottageBook_delete(?)"; 
      try {
        $sth = $this->db->prepare($sql);
        $sth->bindParam(1, $IdNum, PDO::PARAM_INT);
        $sth->execute();
        $count = $sth->rowCount();
        if( $count ) {
          // $this->ProcessLog_insert2('I', 'MGF2', 'dbFuncs.CottageBook_delete', null, $sql . ' IdNum: ' . $IdNum);
          return $count;
        }
        else {
          $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.CottageBook_delete','Deletion failed', $sql . ' IdNum: ' . $IdNum);
          return "ERROR - Deletion failed";
        }
      }
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E','MGF2','dbFuncs.CottageBook_delete', $e->getMessage(), $sql. ' IdNum: ' . $IdNum);
        return 'ERROR - ' . $e->getMessage();
      }

    }


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
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_Insert', $e->getMessage(), $sql);
        $returnArray['success'] = false;
        $returnArray['errorMess'] = $e->getMessage();
      }
      finally {
        return $returnArray;
      }
    }


    // insert a new CottageBook row
    function CottageBook_insert( $DateSat, $CottageNum, $FirstNight, $LastNight, $Rental, $Notes, $bookingRef )
    { 

      $returnArray = array('success'      =>true,
                          'insertedIdNum' =>0,
                          'errorMess'     =>null);

      $sql = "call spCottageBook_Insert(?,?,?,?,?,?,?)";

      try {
        $sth = $this->db->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
        $sth->bindParam(1, $DateSat,      PDO::PARAM_STR);
        $sth->bindParam(2, $CottageNum,   PDO::PARAM_INT);
        $sth->bindParam(3, $FirstNight,   PDO::PARAM_STR);
        $sth->bindParam(4, $LastNight,    PDO::PARAM_STR);
        $sth->bindParam(5, $bookingRef,   PDO::PARAM_STR,4);
        $sth->bindParam(6, $Rental,       PDO::PARAM_INT);
        $sth->bindParam(7, $Notes,        PDO::PARAM_STR);
        $sth->execute();

        $returnArray['insertedIdNum'] = $sth->fetchColumn();

        // check for SQL id returning 0
        if($returnArray['insertedIdNum'] == 0 ) {
          $this->ProcessLog_insert2('E','MGF2','dbFuncs.spCottageBook_Insert','Insert failed', $sql);
          $returnArray['success'] = false;
          $returnArray['errorMess'] = 'ERROR - Insert failed; $returnArray[insertedIdNum] :' . $returnArray['insertedIdNum'] ;
        }
      }
      catch (PDOException $e) {
        $this->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_Insert', $e->getMessage(), $sql);
        $returnArray['success'] = false;
        $returnArray['errorMess'] = 'ERROR - ' . $e->getMessage();
      }
      finally{
        return $returnArray;
      }
    }
    
}

$dbFuncs = new dbFuncs($db);

?>
