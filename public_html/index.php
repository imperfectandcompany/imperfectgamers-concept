<?php
include("../loader.php");
include ('../config.php');
if(isset($BACKEND)){
include('../backend/'.$BACKEND.'.php');
}
?>
<!doctype html>
<html>
  <?php include_once('head.php');?>
  <body class="w-full max-w-8xl mx-auto">
    <div class="flex flex-col">
      <?php if(isset($HEADER)): ?>
       <header id="header" class="z-10 text-center justify-center" style="touch-action: none; top: 0px;">
       <?php include('../header/'.$HEADER.'.php');?>
      </header>
      <?php endif; ?>
      <?php if(isset($FRONTEND)): ?>
      <main style="-webkit-overflow-scrolling:touch">
        <?php include('../frontend/'.$FRONTEND.'.php');?>
      </main>
      <?php endif; ?>
  
      <?php if(isset($FOOTER)): ?>
      <footer id="footer" class="bg-white dark:bg-dark dark:text-light border-gray-300 dark border-dark inset-x-0 bottom-0 text-center z-50 flex lg:hidden" style="touch-action: none; bottom: 0px;">

      </footer>
      <?php endif; ?>
    </div>
  </body>
</html>
