<?php
include("koneksi.php");

class User
{
    protected $password;
    public $username;
    public $fullname;
    public function __construct($username,$password, $fullname)
    {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
    }
}

class UserManager extends User
{
    // encapsulasi
    protected $conn;
    public function __construct($conn, $password, $username, $fullname)
    {
        parent::__construct($password, $username, $fullname);
        $this->conn = $conn;
    }

    public function createUser()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $queri = mysqli_query($this->conn, "INSERT INTO tb_user (fullname, username, password) VALUES ('$this->fullname','$this->username','$hashedPassword')");
        return $queri;
    }
}

// extends itu inheritance
class Login extends User
{
    protected $conn;
    public function __construct($conn, $username, $password)
    {
        parent::__construct($username, $password, '');
        $this->conn = $conn;
    }

    public function loginUser()
    {
        $query = mysqli_query($this->conn,"SELECT * FROM tb_user WHERE username = '$this->username'");
        $userData = mysqli_fetch_assoc($query);
        if ($userData) {
            if (password_verify($this->password, $userData["password"])) {
                session_start();
                $_SESSION["username"] = $this->username;
                header("location:tiket/index.php");
            } else {
                header("location:login.php?pesan=password salah");
            }
        } else {
            header("location:login.php?pesan=username salah");
        }
    }
}

// Kelas baru untuk polymorphism
class Authenticator extends User   
{
// Membuat kelas baru atau turunan dari kelas 'User' 

    public function authenticate()
    {
        echo "Otentikasi untuk pengguna '$this->username' sedang berlangsung...";
    }
}
// Mendefinisikan metode authenticate() dalam kelas Authenticator. Metode ini mencetak pesan otentikasi yang mencakup nama pengguna ($this->username)nilai properti yang diwarisi dari kelas User.

// Contoh penggunaan polymorphism
$authenticator = new Authenticator('ship_user', 'ship_password', 'Ship User');
$authenticator->authenticate();
?>
<!-- 
- Membuat objek baru $authenticator dengan memberikan nilai awal untuk properti username, password, dan fullname.

- Memanggil metode authenticate() pada objek $authenticator. Metode ini dicetak untuk menunjukkan pesan otentikasi.
 -->


<!--
- Dalam konsep polymorphism, objek dari kelas turunan dapat dianggap objek dari kelas dasar.

- Objek dari kelas turunan (Authenticator) yakni objeknya itu authenticate dapat dianggap sebagai objek, dari kelas dasar (User), 
  karena Authenticator adalah turunan dari User.
 -->