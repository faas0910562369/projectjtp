<?php
session_start(); // เริ่ม session

// ลบ session ทั้งหมด
session_unset();
session_destroy();

// Redirect ไปยังหน้า login
header("Location: login.php");
exit(); // หยุดการทำงานของสคริปต์ทันที
?>