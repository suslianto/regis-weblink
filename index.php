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
  <meta content=">Registration - Festival HHSSE PLN" property="og:title">
  <meta content="Hai!, Selamat datang di Festival HSSE PLN, Daftarkan diri anda untuk mendapatkan akses" property="og:description">
  <meta content="assets/img/favicon.jpg" property="og:image">
  <title>Registration - Festival HHSSE PLN</title>
  <link rel="stylesheet" href="assets/css/style/style.css">
  <link rel="stylesheet" href="assets/css/style/styl.css">
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
    $telpon = @$_POST["telpon"];
    $jabatan = @$_POST["jabatan"];
    $divisi = @$_POST["divisi"];
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
            $lurah = "UPDATE pers_attribute_ext SET attr_value13 = '$telpon' WHERE person_id = '$res[0]'";
            $harah = pg_query($lurah) or die("Error:". pg_last_error());
            $reh = pg_fetch_row($harah);
            $kot = "UPDATE pers_attribute_ext SET attr_value14 = '$jabatan' WHERE person_id = '$res[0]'";
            $hakot = pg_query($kot) or die("Error:". pg_last_error());
            $rekot = pg_fetch_row($hakot);
            $mat = "UPDATE pers_attribute_ext SET attr_value6 = '$divisi' WHERE person_id = '$res[0]'";
            $hamat = pg_query($mat) or die("Error:". pg_last_error());
            $remat = pg_fetch_row($hamat);
            //print_r($re);
            //print_r($re);
            echo "
                    <script>
                        Toast.fire({
                          icon: 'success',
                          title: 'Registrasi successfully'
                        })
                        setTimeout(function() {window.location.href = '/regis-weblink';}, 2000);
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
  <center><img src="assets/img/logo-pln2.png" alt="Logo PLN" class="img-fluid"/></center>
    </div>
    <br>
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="title">Regis Festival HSSE PLN</div>
      <div class="user-details">
        <div class="input-box">
          <span class="details">NAMA LENGKAP</span>
          <input type="text" name="name" placeholder="Masukan Nama Lengkap" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">NIP</span>
          <input type="text" name="nip" placeholder="Masukan NIP" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">UNIT / DIVISI</span>
          <input type="text" name="divisi" placeholder="Masukan Unit / Divisi" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">JABATAN</span>
          <input type="text" name="jabatan" placeholder="Masukan Jabatan" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">NO TELPON</span>
          <input type="number" name="telpon" placeholder="Masukan No Telpon" onkeyup="this.value = this.value.toUpperCase()" >
        </div>
        <div class="input-box">
          <span class="details">Upload Foto / Selfie</span>
          <div class="fileUploadInput">
          <label>✨ Pastikan wajah terlihat jelas</label>
          <input type="file" name="filename" required/>
          <button>+</button></div>
        </div>
      </div>
      <div class="gender-details">
        <input type="radio" name="gender" id="dot-1" value="M">
        <input type="radio" name="gender" id="dot-2" value="F">
        <input type="radio" name="gender" id="dot-3" value="M">
        <span class="gender-title">Gender</span>
        <div class="category">
          <label for="dot-1">
          <span class="dot one"></span>
          <span class="gender">Pria</span>
        </label>
        <label for="dot-2">
          <span class="dot two"></span>
          <span class="gender">Wanita</span>
        </label>
        <label for="dot-3">
          <span class="dot three"></span>
          <span class="gender">Tidak ingin memberi tahu</span>
          </label>
        </div>
      </div>
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
