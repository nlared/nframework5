<?
//$developermode=true;
require 'include.php';
$tmp=(array)$user->sessions;
//unset($tmp[session_id()]);
$tmp=array_diff($tmp, [session_id()]);

$m->{$config['sitedb']}->endpoints->deleteOne(['_id'=>(string)session_id()]);
$user->sessions=$tmp;

unset($user);
unset($_SESSION['user']);
unset($_SESSION['emisor']);
unset($_SESSION['primerinicio']);
session_regenerate_id(true); 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
header('Location: '.(isset($_GET['to'])?$_GET['to']:'/'));
?>
<style>
	@media (min-width: 200px) {
	    body {
		  /* Location of the image */
			background-image: url(../image/Login-cel-web.png);	background-position:center center !important;
			width: 100%;
	        height:100%;
	        background-size:100% 100%;
	        font-family: "Avenir Next" !important;
		}
	}
	
	
	@media (min-width: 600px) {
	    body {
		  /* Location of the image */
			background-image: url(../image/Login-web-2.png);	background-position:center center !important;
			width: 100%;
	        height:100%;
	        background-size:100% 100%;
	        font-family: "Avenir Next" !important;
		}
	}
	
</style>
<body>
<div class="h-vh-100 bg-brandColor">
    <div class="login-form col-xs-6 padding20 block-shadow">
        <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow"
      data-role="validator"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm">
        	<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
		    <h2 class="text-light">Cerrando sesión</h2>
		    <hr class="thin mt-4 mb-4 bg-white">
		    <div class="form-group">
                <a href="/" class="button primary full-size">Cerrar</a>
            </div>
        </form>
    </div>
</div>
</body>
<script>
window.location.href = '<?=(isset($_GET['to'])?$_GET['to']:'/')?>';
</script>
<?
exit();
?>