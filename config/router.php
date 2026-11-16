<?php
// config/router.php

class Router
{
   private static $routes = [];

   // Menampung route dengan method GET
   public static function get($path, $callback)
   {
      self::$routes['GET'][$path] = $callback;
   }

   // Menampung route dengan method POST
   public static function post($path, $callback)
   {
      self::$routes['POST'][$path] = $callback;
   }

   // Menjalankan dan mencocokkan URL
   public static function run()
   {
      // Ambil method request (GET/POST)
      $method = $_SERVER['REQUEST_METHOD'];

      // Ambil URL path saat ini dan bersihkan dari query string (?page=1 dll)
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

      // Jika URL yang diakses adalah file statis asli (CSS, JS, Gambar), biarkan PHP server yang handle
      if ($method === 'GET' && file_exists(__DIR__ . '/..' . $path) && !is_dir(__DIR__ . '/..' . $path)) {
         return false;
      }

      // Cek apakah route terdaftar
      if (isset(self::$routes[$method][$path])) {
         $callback = self::$routes[$method][$path];

         // Jika callback berbentuk fungsi biasa (closure)
         if (is_callable($callback)) {
            return call_user_func($callback);
         }
      }

      // Jika URL tidak ditemukan (404)
      http_response_code(404);
      echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
      exit();
   }
}
