<?php
//session_start();
//if (isset($_SESSION["sesi"])){
//  header("Location: index.php"); die();
//}
error_reporting(1);
$host = "192.168.100.100";
$base = "biosecurity-boot";
$user = "root";
$pwd = "ZKTeco##123";
$head = array('Content-Type: application/json;charset=UTF-8');
$co = pg_connect("host=".$host." port=5442 dbname=".$base." user=".$user." password=".$pwd) or die("Failed:".pg_last_error()."<br/>");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="favicon.jpg" rel="shortcut icon" type="image/png">
  <meta content="indonesia, power, indonesiapower, powerindonesia, indonesia power, power indonesia, pln, pln indonesia power," name="keywords">
  <meta content="Hai!, selamat datang, daftarkan diri anda untuk mendapatkan akses" name="description">
  <meta content="summary" name="twitter:card">
  <meta content="indonesia" property="og:site_name">
  <meta content="indonesia" property="og:title">
  <meta content="Hai!, selamat datang, daftarkan diri anda untuk mendapatkan akses" property="og:description">
  <meta content="favicon.jpg" property="og:image">
  <title>Registration - Festival HHSSE PLN</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="styl.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <script src="sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="sweetalert/sweetalert2.min.css" type="text/text/css" />
  <script type="text/javascript">
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
  </script>
  <script type="text/javascript">
    function CheckColors(val){
      var element=document.getElementById('color');
      if(val=='pick a color'||val=='others')
        element.style.display='block';
      else  
        element.style.display='none';
      }
      function uploadFile(target) {
        document.getElementById("file-name").innerHTML = target.files[0].name;
      }
  </script>
  <style>
  .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
      width: auto;
  }
  </style>
  </head>
<body>
  <div class="container">
  <?php
  if (isset($_POST["submit"])){
    $name = @$_POST["name"];
    $nip = @$_POST["nip"];
    $kelurahan = @$_POST["lurah"];
    $kota = @$_POST["kota"];
    $alamat = @$_POST["alamat"];
    $kecamatan = @$_POST["kecamatan"];
    $provinsi = @$_POST["provinsi"];
    $plat = @$_POST["plat"];
    $gender = @$_POST["gender"];
    //$level = @$_POST["level"];
    $time = new Datetime("now");
    $pin = rand(50000, 100000);
    $pins = (string)$pin;
    $target_dir = __DIR__.'/file_tools/';
    //echo $target_dir;
    $fileName = $_FILES["filename"]["name"];
    $tempPath = $_FILES["filename"]['tmp_name'];
    //$myfile = @$_POST["filename"];
    if ($_POST["dept"] == "others") {
      $dep = @$_POST["newdept"];
        $nom = 0;
        $dept = 0;
        $nilai = array();
        while (true) {
            $nom += 1;
            if ($nom >= 30) {
            break;
            }
            $head = array('Accept: application/json');
            $ch = curl_init();
        curl_setopt_array($ch, array(
              CURLOPT_SSL_VERIFYHOST => 0,
              CURLOPT_SSL_VERIFYPEER => 0,
              CURLOPT_FOLLOWLOCATION => 0,
              CURLOPT_RETURNTRANSFER => 1,
              CURLOPT_URL => "https://192.168.100.100:8098/api/department/get/".(string)$nom."?access_token=8D0A28EE8FB83ECB00B5FB59778D5AC2",
              CURLOPT_HTTPHEADER => $head,
            ));
        $ge = curl_exec($ch);
        $jsn = json_decode($ge, true);
        if ($jsn["code"] == 0) {
                  array_push($nilai, intval($jsn["data"]["code"]));
            }
          }
        while (true) {
            $dept += 1;
              if (!in_array($dept, $nilai)) {
                  $field = json_encode(array(
                        "code"=>$dept,
                        "name"=>$dep,
                        "parentCode"=>"",
                        "sortNo"=>999999
                    ));
                  $url_add = "https://192.168.100.100:8098/api/department/add?access_token=8D0A28EE8FB83ECB00B5FB59778D5AC2";
                    $header = array('Accept: application/json','Content-Type: application/json');
                    $ceh = curl_init();
                    curl_setopt($ceh, CURLOPT_SSL_VERIFYHOST, 0);
                  curl_setopt($ceh, CURLOPT_SSL_VERIFYPEER, 0);
                  curl_setopt($ceh, CURLOPT_FOLLOWLOCATION, 0);
                    curl_setopt($ceh, CURLOPT_URL, $url_add);
                    curl_setopt($ceh, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($ceh, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ceh, CURLOPT_POSTFIELDS, $field);
                    $respon = curl_exec($ceh);
                    $data_dep = json_decode($respon, true);
                    // var_dump($data_dep);
                    if ($data_dep['message'] == 'Department name already exists') {
                    echo "
                          <script>
                            Toast.fire({
                                icon: 'error',
                                title: 'Department name already exists'
                              })
                            setTimeout(function() {window.location.href = '/';}, 2000);
                          </script>
                        ";
                    } else if ($data_dep['message'] == 'success') {}
                    break;
              }
        }
    } else {
      $dept = @$_POST["dept"];
    }
    include 'dbconfig.php'; 
    $data = json_decode(file_get_contents("php://input"), true);
    list($width, $height, $type, $attr) = getimagesize($tempPath);
    $query = "ALTER TABLE file AUTO_INCREMENT=0";
    mysqli_query($conn, $query); 
    $query = mysqli_query($conn,'INSERT into file (file) VALUES("'.$fileName.'")');
    $query = "SELECT * FROM file";
    $hasil = mysqli_query($conn, $query);
    $r = mysqli_fetch_array($hasil);
    if ($width >= 3000) {
          include('compress.php');
          CreateThumbnail($tempPath,$target_dir.$fileName,3000);
          $imagedata = file_get_contents($target_dir.$r["file"]);
    } else {
          move_uploaded_file($tempPath, $target_dir . $fileName);
          $imagedata = file_get_contents($target_dir.$fileName);
    }
    // store_uploaded_image('filename', 4000, 3000);
    // uploadAndResize('filename', $upload_path, 3000);
    if(!isset($errorMSG)) {
        $base64 = base64_encode($imagedata);
        // var_dump($base64);
      /** echo json_encode(array("message" => "File Uploaded Successfully", "status" => true)); */
      $url = "https://192.168.100.100:8098/api/person/add?access_token=8D0A28EE8FB83ECB00B5FB59778D5AC2";
        $parameters = json_encode(array(
          "accEndTime"=> "",
          "accLevelIds"=>"1",
          "accStartTime"=> "",
          "birthday"=> "",
          "carPlate"=> $plat,
          "cardNo"=> "",
          "certNumber"=> "",
          "certType"=> 2,
          "deptCode"=> $dept,
          "email"=> "",
          "gender"=> $gender,
          "hireDate"=> "",
          "isDisabled"=> false,
          "isSendMail"=> false,
          "lastName"=> "",
          "mobilePhone"=> "",
          "name"=> $name,
          "personPhoto"=> $base64,
          "personPwd"=> "",
          "pin"=> $pins,
          "ssn"=> "111111",
          "supplyCards"=> ""
        ));
        $head = array('Accept: application/json','Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($ch);
        $data_json = json_decode($response, true);
        // var_dump($data_json);
        $files = glob($target_dir.'*');
        foreach ($files as $file) {
              if (is_file($file))
              unlink($file);
        }
        $query = "TRUNCATE TABLE file";
        mysqli_query($conn, $query);
        $query = "ALTER TABLE file DROP id";
        mysqli_query($conn, $query);
        $query = "ALTER TABLE file ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        mysqli_query($conn, $query);
        if ($data_json["message"] == "success") {
            $q = "SELECT * FROM pers_person WHERE pin = '$pins'";
            $has = pg_query($q) or die("Error:". pg_last_error());
            $res = pg_fetch_row($has);
            $qur = "UPDATE pers_attribute_ext SET attr_value4 = '$nip' WHERE person_id = '$res[0]'";
            $ha = pg_query($qur) or die("Error:". pg_last_error());
            $re = pg_fetch_row($ha);
            $lurah = "UPDATE pers_attribute_ext SET attr_value3 = '$kelurahan' WHERE person_id = '$res[0]'";
            $harah = pg_query($lurah) or die("Error:". pg_last_error());
            $reh = pg_fetch_row($harah);
            $kot = "UPDATE pers_attribute_ext SET attr_value7 = '$kota' WHERE person_id = '$res[0]'";
            $hakot = pg_query($kot) or die("Error:". pg_last_error());
            $rekot = pg_fetch_row($hakot);
            $mat = "UPDATE pers_attribute_ext SET attr_value2 = '$alamat' WHERE person_id = '$res[0]'";
            $hamat = pg_query($mat) or die("Error:". pg_last_error());
            $remat = pg_fetch_row($hamat);
            $kec = "UPDATE pers_attribute_ext SET attr_value5 = '$kecamatan' WHERE person_id = '$res[0]'";
            $hacet = pg_query($kec) or die("Error:". pg_last_error());
            $recet = pg_fetch_row($hacet);
            $vinsi = "UPDATE pers_attribute_ext SET attr_value8 = '$provinsi' WHERE person_id = '$res[0]'";
            $havin = pg_query($vinsi) or die("Error:". pg_last_error());
            $revin = pg_fetch_row($havin);
            //print_r($re);
            //print_r($re);
            echo "
                    <script>
                        Toast.fire({
                          icon: 'success',
                          title: 'Registrasi successfully'
                        })
                        setTimeout(function() {window.location.href = 'index.php';}, 2000);
                    </script>
                  ";
          } else if ($data_json["code"] == -63) {
          echo "
                    <script>
                        Toast.fire({
                          icon: 'error',
                          title: 'Face fetection due to:No face detected'
                        })
                    </script>
                  ";
          } else if ($data_json["message"] == "please select file") {
          echo "
                    <script>
                        Toast.fire({
                          icon: 'error',
                          title: 'please select file'
                        })
                    </script>
                  ";
          } else {
            echo "
                    <script>
                        Toast.fire({
                          icon: 'error',
                          title: 'please select file'
                        })
                    </script>
                  ";
            }
    } else {
      echo "
                    <script>
                        Toast.fire({
                          icon: 'error',
                          title: 'please select file'
                        })
                    </script>
                  ";
    }
  }
  ?>
  <div class="content">
    <div>
  <center><img src="logo-pln2.png" alt="Logo PLN" class="img-fluid"/></center>
    </div>
    <br>
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="title">Regis Festival HSSE PLN</div>
      <div class="user-details">
  <!-- <div class="input-box">
          <span class="details">KELURAHAN</span>
          <input type="text" name="lurah" placeholder="Enter your kelurahan" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
  <div class="input-box">
          <span class="details">KECAMATAN</span>
          <input type="text" name="kecamatan" placeholder="Enter your kecamatan" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
  <div class="input-box">
          <span class="details">KOTA</span>
          <input type="text" name="kota" placeholder="Enter your kota" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
  <div class="input-box">
          <span class="details">PROVINSI</span>
          <input type="text" name="provinsi" placeholder="Enter your provinsi" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
  <div class="input-box">
          <span class="details">ALAMAT</span>
          <input type="text" name="alamat" placeholder="Enter your alamat" onkeyup="this.value = this.value.toUpperCase()" >
        </div> -->
        <div class="input-box">
          <span class="details">NAMA LENGKAP</span>
          <input type="text" name="name" placeholder="Masukan Nama Lengkap" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">NIP</span>
          <input type="text" name="nip" placeholder="Enter your nip" onkeyup="this.value = this.value.toUpperCase()" required >
        </div>
        <div class="input-box">
          <span class="details">UNIT / DIVISI</span>
          <input type="text" name="nip" placeholder="Enter your nip" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">JABATAN</span>
          <input type="text" name="nip" placeholder="Enter your nip" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">NO TELPON</span>
          <input type="text" name="nip" placeholder="Enter your nip" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <!--<div class="input-box">
          <span class="details">Department</span>
          <?php
            function select($a, $b = array(), $c = array(), $d = '', $e = '')
            {
            $set='';
            if(is_array($d)){
                foreach ($d as $key => $value) {
                  $set.=' '.$key.'="'.$value.'" ';
                }
              }
            else{
                $set=$d;
              }
              $data = '';
            if (!is_array($c)) {
                $c = array(
                      $c
                  );
              }
            if (count($c) === 0) {
                  if (isset($_POST[$a])) {
                      $c = array(
                          $_POST[$a]
                      );
                  }
              }
              if ($d != '') {
                  $d = ' ' . $d;
              }
              if ($e != '') {
                  $e = ' ' . $e;
              }
              foreach ($b as $key => $val) {
                  $sel = (in_array($key, $c)) ? ' selected="selected"' : '';
                  $data .= "\t\t\t\t\t\t<option value=\"" . $key . "\"" . $sel . ">" . (string) $val . "</option>\n";
              }
              return '<select class='."selectpicker".' name="' . $a . '" '. $set . '"' . $e . ' onchange='."CheckColors(this.value);".' data-dropup-auto='."false".'>' . "\n" . $data . "\t\t\t\t\t" . '<option value='."others".'>OTHERS</option></select>' . "\n\t\t\t\t";

            }
          $op=null;
          $qu = "SELECT * FROM auth_department";
          $resu = pg_query($qu) or die("Error:". pg_last_error());
          //$resul = pg_fetch_row($resu);
          //print_r($resul);
          while ($row = pg_fetch_row($resu)) {
              $op[$row[13]]=$row[15];
              // array_push($nilai, intval($json["data"]["code"]));
            }
            echo select('dept',$op,1);
            ?>
            <input type="text" name="newdept" id="color" style='display:none;' placeholder="Enter your department" onkeyup="this.value = this.value.toUpperCase()"/>
        </div> -->
        <!-- <div class="input-box">
          <span class="details">License Plate</span>
          <input type="text" name="plat" placeholder="Enter license plate" onkeyup="this.value = this.value.toUpperCase()" >
        </div> -->
        <div class="input-box">
          <span class="details">Upload Your photo/selfie</span>
          <div class="fileUploadInput">
          <label>✨ Pastikan wajah terlihat jelas</label>
          <input type="file" name="filename" required/>
          <button>+</button></div>
        </div>
      </div>
      <!-- <div class="gender-details">
        <input type="radio" name="gender" id="dot-1" value="M">
        <input type="radio" name="gender" id="dot-2" value="F">
        <input type="radio" name="gender" id="dot-3" value="M">
        <span class="gender-title">Gender</span>
        <div class="category">
          <label for="dot-1">
          <span class="dot one"></span>
          <span class="gender">Male</span>
        </label>
        <label for="dot-2">
          <span class="dot two"></span>
          <span class="gender">Female</span>
        </label>
        <label for="dot-3">
          <span class="dot three"></span>
          <span class="gender">Prefer not to say</span>
          </label>
        </div>
      </div> -->
      <br>
      <br>
      <div class="button">
        <input type="submit" name="submit" value="Register">
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script>
<script>
  $('#lang').selectpicker();
</script>
</body>
</html>
