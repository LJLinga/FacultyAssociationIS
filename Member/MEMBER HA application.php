<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Health Aid Application</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php 

    session_start();

    if ($_SESSION['usertype'] != 2) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }

    $message = NULL;
    $idnum = $_SESSION['idnum'];
    $siblingfirstarray = array();
    $siblinglastarray = array();
    $siblingmiddlearray = array();
    $siblingdayarray = array();
    $siblingmontharray = array();
    $siblingyeararray = array();
    $siblingbirthdatearray = array();
    $siblingsexarray = array();
    $siblingstatusarray = array();
    $childdayarray = array();
    $childmontharray = array();
    $childyeararray = array();
    $childfirstarray = array();
    $childlastarray = array();
    $childmiddlearray = array();
    $childbirthdatearray = array();
    $childsexarray = array();
    $childstatusarray = array();
    $appstatusid = 1;

    require_once('../mysql_connect_FA.php');
    $queryCiv = "SELECT CIV_STATUS FROM MEMBER WHERE MEMBER_ID = '{$idnum}'";
    $resultCiv = mysqli_query($dbc, $queryCiv);
    $rowCiv = mysqli_fetch_array($resultCiv);

    $queryForm = "SELECT HEALTH_AID_STATUS FROM MEMBER WHERE MEMBER_ID = '{$idnum}'";
    $resultForm = mysqli_query($dbc, $queryForm);
    $rowForm = mysqli_fetch_array($resultForm);

    if ($rowForm['HEALTH_AID_STATUS'] == 1) { /* PENDING */

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member healthaid_pending.php");

    }

    else if ($rowForm['HEALTH_AID_STATUS'] == 2) { /* ACCEPTED */

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member healthaid_approved.php");

    }

    else if ($rowForm['HEALTH_AID_STATUS'] == 3) { /* REJECTED */

        echo "<font color=\"red\"> Your last application for Health Aid Benefits was rejected. You can apply for another one.</font>";

    }

    /* check if empty fields */

    if (isset($_POST['submit'])) {

        $flag = 0;

        if (empty($_POST['fatherfirst']) || empty($_POST['fathermiddle']) || empty($_POST['fatherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fathermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherlast'])) {

            $message.='<p>You forgot to fill up all name fields or inputed an invalid input on Father\'s info!';
            $flag++;

        }

        else {

            $fatherfirst = $_POST['fatherfirst'];
            $fathermiddle = $_POST['fathermiddle'];
            $fatherlast = $_POST['fatherlast'];

        }

        if (empty($_POST['fathermonth']) || empty($_POST['fatherday']) || empty($_POST['fatheryear'])) {

            $message.='<p>You forgot to fill up all birth date fields on Father\'s info!';
            $flag++;

        }

        else {

            $fatherbirthdate = $_POST['fatheryear'] . "-" . $_POST['fathermonth'] . "-" . $_POST['fatherday'];

        }

        if (empty($_POST['motherfirst']) || empty($_POST['mothermiddle']) || empty($_POST['motherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['mothermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherlast'])) {

            $message.='<p>You forgot to fill up all name fields or inputed an invalid input on Mother\'s info!';
            $flag++;

        }

        else {

            $motherfirst = $_POST['motherfirst'];
            $mothermiddle = $_POST['mothermiddle'];
            $motherlast = $_POST['motherlast'];

        }

        if (empty($_POST['mothermonth']) || empty($_POST['motherday']) || empty($_POST['motheryear'])) {

            $message.='<p>You forgot to fill up all birth date fields on Mother\'s info!';
            $flag++;

        }

        else {

            $motherbirthdate = $_POST['motheryear'] . "-" . $_POST['mothermonth'] . "-" . $_POST['motherday'];

        }

        if (!isset($_POST['hasSibling'])) {

            if (isset($_POST['siblingstatus'])) {

                foreach ($_POST['siblingstatus'] as $siblingstatus) {

                        array_push($siblingstatusarray, $siblingstatus);

                }

            }

            else {

                $message.='<p>You forgot to choose the status on child\'s info!';
                $flag++;

            }

            foreach ($_POST['siblingfirst'] as $siblingfirst) {

                if (empty($siblingfirst) || !preg_match("/^[a-zA-Z ]*$/", $siblingfirst)) {

                    echo $siblingfirst + " first ";
                    $message.='<p>You forgot to fill up a first name field or inputed an invalid input on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblingfirstarray, $siblingfirst);

                }

            }

            foreach ($_POST['siblinglast'] as $siblinglast) {

                if (empty($siblinglast) || !preg_match("/^[a-zA-Z ]*$/", $siblinglast)) {

                    $message.='<p>You forgot to fill up a last name field or inputed an invalid input on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblinglastarray, $siblinglast);

                }

            }

            foreach ($_POST['siblingmiddle'] as $siblingmiddle) {

                if (empty($siblingmiddle) || !preg_match("/^[a-zA-Z ]*$/", $siblingmiddle)) {

                    $message.='<p>You forgot to fill up a middle name field or inputed an invalid input on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblingmiddlearray, $siblingmiddle);

                }

            }

            foreach ($_POST['siblingmonth'] as $siblingmonth) {

                if (empty($siblingmonth)) {

                    $message.='<p>You forgot to fill up a month field on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblingmontharray, $siblingmonth);

                }

            }

            foreach ($_POST['siblingday'] as $siblingday) {

                if (empty($siblingday)) {

                    $message.='<p>You forgot to fill up a day field on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblingdayarray, $siblingday);

                }

            }

            foreach ($_POST['siblingyear'] as $siblingyear) {

                if (empty($siblingyear)) {

                    $message.='<p>You forgot to fill up a year field on siblings\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($siblingyeararray, $siblingyear);

                }

            }

            if (isset($_POST['siblingsex'])) {

                foreach ($_POST['siblingsex'] as $siblingsex) {

                        array_push($siblingsexarray, $siblingsex);

                }

            }

            else {

                $message.='<p>You forgot to choose the sex on sibling\'s info!';
                $flag++;

            }

        }

    if ($rowCiv['CIV_STATUS'] != 1) {

        if (!isset($_POST['hasChild'])) {

            if (isset($_POST['childstatus'])) {

                foreach ($_POST['childstatus'] as $childstatus) {

                        array_push($childstatusarray, $childstatus);

                }

            }

            else {

                $message.='<p>You forgot to choose the status on child\'s info!';
                $flag++;

            }

            foreach ($_POST['childfirst'] as $childfirst) {

                if (empty($childfirst) || !preg_match("/^[a-zA-Z ]*$/", $childfirst)) {

                    $message.='<p>You forgot to fill up a first name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childfirstarray, $childfirst);

                }

            }

            foreach ($_POST['childlast'] as $childlast) {

                if (empty($childlast) || !preg_match("/^[a-zA-Z ]*$/", $childlast)) {

                    $message.='<p>You forgot to fill up a last name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childlastarray, $childlast);

                }

            }

            foreach ($_POST['childmiddle'] as $childmiddle) {

                if (empty($childmiddle) || !preg_match("/^[a-zA-Z ]*$/", $childmiddle)) {

                    $message.='<p>You forgot to fill up a middle name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childmiddlearray, $childmiddle);

                }

            }

            foreach ($_POST['childmonth'] as $childmonth) {

                if (empty($childmonth)) {

                    $message.='<p>You forgot to fill up a month field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childmontharray, $childmonth);

                }

            }

            foreach ($_POST['childday'] as $childday) {

                if (empty($childday)) {

                    $message.='<p>You forgot to fill up a day field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childdayarray, $childday);

                }

            }

            foreach ($_POST['childyear'] as $childyear) {

                if (empty($childyear)) {

                    $message.='<p>You forgot to fill up a year field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childyeararray, $childyear);

                }

            }

            if (isset($_POST['childsex'])) {

                foreach ($_POST['childsex'] as $childsex) {

                        array_push($childsexarray, $childsex);

                }

            }

            else {

                $message.='<p>You forgot to choose the sex on child\'s info!';
                $flag++;

            }

        }

    }

        if ($flag == 0) {

            $idnum = $_SESSION['idnum'];

            for ($x = 0; $x < count($siblingmontharray); $x++) {

                $siblingbirthday = $siblingyeararray[$x] . "-" . $siblingmontharray[$x] . "-" . $siblingdayarray[$x];

                array_push($siblingbirthdatearray, $siblingbirthday);

            }

            for ($y = 0; $y < count($childmontharray); $y++) {

                $childbirthday = $childyeararray[$y] . "-" . $childmontharray[$y] . "-" . $childdayarray[$y];

                array_push($childbirthdatearray, $childbirthday);

            }

                $queryMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = '{$appstatusid}' WHERE MEMBER_ID = '{$idnum}';";
                $resultMem = mysqli_query($dbc, $queryMem);

            /* father insert */

                $fatherstatus = $_POST['fatherstatus'];

                $queryFather = "INSERT INTO FATHER (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS_ID) 

                VALUES ('{$idnum}', '{$fatherlast}', '{$fatherfirst}', '{$fathermiddle}', CAST('{$fatherbirthdate}' AS DATE), '{$fatherstatus}');";

                $resultFather = mysqli_query($dbc, $queryFather);

            /* mother insert */

                $motherstatus = $_POST['motherstatus'];

                $queryMother = "INSERT INTO MOTHER (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS_ID) 

                VALUES ('{$idnum}', '{$motherlast}', '{$motherfirst}', '{$mothermiddle}', CAST('{$motherbirthdate}' AS DATE), '{$motherstatus}');";

                $resultMother = mysqli_query($dbc, $queryMother);

            /* sibling insert */

                for ($s = 0; $s < count($siblinglastarray); $s++) {

                    $siblingf = $siblingfirstarray[$s];
                    $siblingl = $siblinglastarray[$s];
                    $siblingm = $siblingmiddlearray[$s];
                    $siblings = $siblingsexarray[$s];
                    $siblingb = $siblingbirthdatearray[$s];
                    $siblingst = $siblingstatusarray[$s];

                    echo $siblingf . " " . $siblingl . " " . $siblingm . " " . $siblings . " " . $siblingb . " " . $siblingst;

                    $querySibling = "INSERT INTO SIBLINGS (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, SEX, BIRTHDATE, STATUS_ID)

                    VALUES ('{$idnum}', '{$siblingl}', '{$siblingf}', '{$siblingm}', '{$siblings}', CAST('{$siblingb}' AS DATE), '{$siblingst}');";

                    $resultSibling = mysqli_query($dbc, $querySibling);

                }

            /* children insert */

            if ($rowCiv['CIV_STATUS'] != 1) {

                for ($c = 0; $c < count($childlastarray); $c++) {

                    $childf = $childfirstarray[$c];
                    $childl = $childlastarray[$c];
                    $childm = $childmiddlearray[$c];
                    $childs = $childsexarray[$c];
                    $childb = $childbirthdatearray[$c];
                    $childst = $childstatusarray[$c];

                    $queryChild = "INSERT INTO CHILDREN (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, SEX, BIRTHDATE, STATUS_ID)

                    VALUES ('{$idnum}', '{$childl}', '{$childf}', '{$childm}', '{$childs}', CAST('{$childb}' AS DATE), '{$childst}');";

                    $resultChild = mysqli_query($dbc, $queryChild);


                }

            }

            $queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
                             VALUES ({$_SESSION['idnum']}, 0, NOW(), 1, 'Health Aid Application Submitted')";

            $resultTransaction = mysqli_query($dbc, $queryTransaction);

        }

    }

?>
<body>

    <div id="wrapper">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            
            <div class="navbar-header"> <!-- Logo -->
                
                <img src="images/I-FA Logo Edited.png" id="ifalogo">
            
            
            <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->
                
                <li class="dropdown sideicons">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>

                    <ul class="dropdown-menu alert-dropdown">

                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>

                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#">View All</a>
                        </li>

                    </ul>

                </li>

                <li class="dropdown sideicons">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>

                        </li>

                        <li class="divider"></li>

                        <li>

                            <a href="login.html"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>
            </div>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">

                    <li id="top">

                        <a href="MEMBER dashboard.html"><i class="fa fa-area-chart" aria-hidden="true"></i> Overview</a>

                    </li>

                    <li>

                        <a href="javascript:;" data-toggle="collapse" data-target="#applicationformsdd"><i class="fa fa-wpforms" aria-hidden="true"></i> Application Forms <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="applicationformsdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP application.html"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Application</a>
                            </li>

                            <li>
                                <a href="MEMBER HA application.html"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Application</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME form.html"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Member Application</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER BANKLOAN list.html"><i class="fa fa-dollar" aria-hidden="true"></i> Bank Loans</a>

                    </li>

                    <li>

                    <a href="MEMBER DEDUCTION summary.html"><i class="fa fa-book" aria-hidden="true"></i> Salary Deduction Summary</a>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#loantrackingdd"><i class="fa fa-money" aria-hidden="true"></i> Loan Tracking <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="loantrackingdd" class="collapse">

                            <li>
                                <a href="MEMBER FALP summary.html"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loan</a>
                            </li>

                            <li>
                                <a href="MEMBER BANKLOAN summary.html"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                    <a href="javascript:;" data-toggle="collapse" data-target="#servicessummarydd"><i class="fa fa-university" aria-hidden="true"></i> Services Summary <i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="servicessummarydd" class="collapse">

                            <li>
                                <a href="MEMBER HA summary.html"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Summary</a>
                            </li>

                            <li>
                                <a href="MEMBER LIFETIME summary.html"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Membership Summary</a>
                            </li>

                        </ul>

                    </li>

                    <li>

                        <a href="MEMBER AUDITRAIL.html"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

                    </li>

                    <li>

                        <a href="MEMBER FILEREPO.html"><i class="fa fa-folder" aria-hidden="true"></i> File Repository</a>

                    </li>

                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row"> <!-- Title & Breadcrumb -->
                    
                    <div class="col-lg-12">
                        
                        <h1 class="page-header">Health Aid Application Form</h1>

                    </div>

                </div>

                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- SERVER SELF -->

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Father's Information</h3>

                        <label>
                        <span class="labelspan">Father's First Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fatherfirst">
                        </label>

                        <label>
                        <span class="labelspan">Father's Middle Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fathermiddle">
                        </label>

                        <label>
                        <span class="labelspan">Father's Last Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fatherlast">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <p class="dbirthlabel"><b>Date of Birth</b></p>
                        <select name="fathermonth" class="form-control datedropdown health">

                            <option value="">Select Month</option>
                            <option value=""> ----- </option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>

                        </select>
                        </label>

                        <label>
                        <select name="fatherday" class="form-control datedropdown health">

                            <option value="">Select Day</option>
                            <option value=""> ----- </option>

                            <?php for($x = 1; $x <= 31; $x++) { ?>

                                <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <select name="fatheryear" class="form-control datedropdown health">

                            <option value="">Select Year</option>
                            <option value=""> ----- </option>

                            <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label>
                        <p class="dbirthlabel">Father's Status</p>
                        <select name="fatherstatus" class="form-control datedropdown health">

                            <option value="1">Normal</option>
                            <option value="0">Deceased</option>

                        </select>
                        </label>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Mother's Information</h3>

                        <label>
                        <span class="labelspan">Mother's First Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="motherfirst">
                        </label>

                        <label>
                        <span class="labelspan">Mother's Middle Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="mothermiddle">
                        </label>

                        <label>
                        <span class="labelspan">Mother's Last Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="motherlast">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <p class="dbirthlabel"><b>Date of Birth</b></p>
                        <select name="mothermonth" class="form-control datedropdown health">

                            <option value="">Select Month</option>
                            <option value=""> ----- </option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>

                        </select>
                        </label>

                        <label>
                        <select name="motherday" class="form-control datedropdown health">

                            <option value="">Select Day</option>
                            <option value=""> ----- </option>

                            <?php for($x = 1; $x <= 31; $x++) { ?>

                                <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <select name="motheryear" class="form-control datedropdown health">

                            <option value="">Select Year</option>
                            <option value=""> ----- </option>

                            <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label>
                        <p class="dbirthlabel">Mother's Status</p>
                        <select name="motherstatus" class="form-control datedropdown health">

                            <option value="1">Normal</option>
                            <option value="0">Deceased</option>

                        </select>
                        </label>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-12">
<!-- Include in the PHP -->
                        <h3 class="healthfieldlabel">Spouse's Information</h3>

                        <label>
                        <span class="labelspan">Spouse's First Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="spousefirst">
                        </label>

                        <label>
                        <span class="labelspan">Spouse's Middle Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="spousemiddle">
                        </label>

                        <label>
                        <span class="labelspan">Spouse's Last Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="spouselast">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <p class="dbirthlabel"><b>Date of Birth</b></p>
                        <select name="spouseday" class="form-control datedropdown health">

                            <option value="">Select Month</option>
                            <option value=""> ----- </option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>

                        </select>
                        </label>

                        <label>
                        <select name="spouseday" class="form-control datedropdown health">

                            <option value="">Select Day</option>
                            <option value=""> ----- </option>

                            <?php for($x = 1; $x <= 31; $x++) { ?>

                                <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <select name="spouseyear"class="form-control datedropdown health">

                            <option value="">Select Year</option>
                            <option value=""> ----- </option>

                            <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label>
                        <p class="dbirthlabel">Spouse's Status</p>
                        <select class="form-control datedropdown health">

                            <option>Normal</option>
                            <option>Deceased</option>

                        </select>
                        </label>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Children's Information</h3>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <font color="red">Note: Please check the checkbox below if you do not have children. Excluding deceased children.</font><p>

                        <p>

                        <input type="checkbox" name="hasChild" value="1"> I don't have children<p>

                    </div>

                </div>

                <div id="childform">
                <div class="childfields">

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <span class="labelspan">Children's First Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="childfirst[]">
                        </label>

                        <label>
                        <span class="labelspan">Children's Last Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="childlast[]">
                        </label>

                        <label>
                        <span class="labelspan">Children's Middle Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="childmiddle[]">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <p class="dbirthlabel"><b>Date of Birth</b></p>
                        <select name="childmonth[]" class="form-control datedropdown health">

                            <option value="">Select Month</option>
                            <option value=""> ----- </option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>

                        </select>
                        </label>

                        <label>
                        <select name="childday[]" class="form-control datedropdown health">

                            <option value="">Select Day</option>
                            <option value=""> ----- </option>

                            <?php for($x = 1; $x <= 31; $x++) { ?>

                                <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <select name="childyear[]" class="form-control datedropdown health">

                            <option value="">Select Year</option>
                            <option value=""> ----- </option>

                            <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                            <?php } ?>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <p class="dbirthlabel">Children's Sex</p>
                        <select name="childsex" class="form-control datedropdown health">

                            <option>Male</option>
                            <option>Female</option>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <p class="dbirthlabel">Children's Status</p>
                        <select name="childstatus" class="form-control datedropdown health">

                            <option>Normal</option>
                            <option>Deceased</option>

                        </select>
                        </label>

                    </div>

                    </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <a href="#" class="btn btn-default addchild" role="button">Add Field</a>
                        <a class="btn btn-default removechild" role="button">Remove Previously Added Fields</a>

                    </div>

               </div>

               <hr>

               <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Siblings's Information</h3>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <font color="red">Note: Please check the checkbox below if you do not have children. Excluding deceased children.</font><p>

                        <p>

                        <input type="checkbox" name="hasSibling" value="1"> I don't have siblings<p>

                    </div>

                </div>

                <div id="siblingform">
                <div class="siblingfields">

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <span class="labelspan">Sibling's First Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fname">
                        </label>

                        <label>
                        <span class="labelspan">Sibling's Last Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fname">
                        </label>

                        <label>
                        <span class="labelspan">Sibling's Middle Name<big class="req"> *</big></span>
                        <input type="text" class="form-control memname" placeholder="Name" name="fname">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                        <p class="dbirthlabel"><b>Date of Birth</b></p>
                        <select class="form-control datedropdown health">

                            <option>Month</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>

                        </select>
                        </label>

                        <label>
                        <select class="form-control datedropdown health">

                            <option>Day</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <select class="form-control datedropdown health">

                            <option>Year</option>
                            <option>1995</option>
                            <option>1994</option>
                            <option>1993</option>
                            <option>1992</option>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <p class="dbirthlabel">Sibling's Sex</p>
                        <select class="form-control datedropdown health">

                            <option>Male</option>
                            <option>Female</option>

                        </select>
                        </label>

                        <label class="yeardrop">
                        <p class="dbirthlabel">Sibling's Status</p>
                        <select class="form-control datedropdown health">

                            <option>Normal</option>
                            <option>Deceased</option>

                        </select>
                        </label>

                    </div>

                </div>

                </div>
                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <a class="btn btn-default addsibling" role="button">Add Field</a>
                        <a class="btn btn-default removesibling" role="button">Remove Previously Added Fields</a>

                    </div>

               </div>

                <div class="row">

                    <div class="col-lg-12">

                        <hr>

                        <input type="submit" name="submit" class="btn btn-success" role="button" value="Apply"></a>
                        <p> &nbsp;

                    </div>

                </div>

            </form>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

            <script>

            $(document).ready(function(){

                var iS = 1;
                var iC = 1;
                var sibfields = $('.siblingfields:first');
                var childfields = $('.childfields:first');

                /* adding child and sibling fields */

                $('body').on('click', '.addsibling', function() {

                    iS++;

                    var section = sibfields.clone().find(':input').each(function() {

                        var newId = this.id + iS;

                        this.id = newId;

                    }).end().appendTo('#siblingform');

                    return false;

                });

                $('body').on('click', '.addchild', function() {

                    iC++;

                    var section2 = childfields.clone().find(':input').each(function() {

                        var newId2 = this.id + iC;

                        this.id = newId2;

                    }).end().appendTo('#childform');

                    return false;

                });

                /* removing child and sibling fields */

                $('#siblingform').on('click', '.removesibling', function() {

                    $this.parent().fadeOut(300, function() {

                        $(this).parent().parent().empty();
                        return false;

                    });

                    return false;

                });

                $('#childform').on('click', '.removechild', function() {

                    $this.parent().fadeOut(300, function() {

                        $(this).parent().parent().empty();
                        return false;

                    });

                    return false;

                });

            });

        </script>

</body>

</html>
