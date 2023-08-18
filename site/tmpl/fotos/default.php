<?php
use Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
$estacion = $_GET['est'];
$db = JFactory::getDbo();
$query = $db
    ->getQuery(true)
    ->select('nombre')
    ->from($db->quoteName('#__estaciones'))
    ->where($db->quoteName('ind_climatologico') . " = " . $db->quote($estacion));

$db->setQuery($query);
$result = $db->loadResult();

$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_mantenimiento.list');
$path = "images/fotos_estaciones/$estacion/";

if(!is_dir($path)){
    echo "<h5>Todavía no hemos creado la carpeta \"$path\" </h5>";
}else{
    $files = [];
    $array_tipo = ["*"];
    foreach ($array_tipo as $tip) :
        $files=array_merge($files,JFolder::files($path,"^.*\.$tip$"));
    endforeach;
?>

<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_mantenimiento&view=estaciones'); ?>" role="button">Volver a estaciones</a>

<section id="galeria">
<div class="text-center">
    <h1>Galería de <?= $result ?></h1>
</div>

<div class="row">
    <?php if(count($files)>0):?>
        <?php $i = 0; ?>
        <?php foreach($files as $file):?>
            <?php
                $info = new SplFileInfo($file);
                $extension = $info->getExtension();
                $validas = array('jpg','jpeg','png','gif','apng','webp','avif','PNG','JPG');
            ?>
            <?php if(in_array($extension,$validas)):?>
            <div class="col-lg-4">
            <a href="#!" data-bs-toggle="modal" data-bs-target="#modalImage<?= $i ?>">
            <img src= "<?= Uri::root() ?><?= $path .$file ?>" alt="">
            </a>

            </div>
            <?php endif ?>
            <?php $i++; ?>
        <?php endforeach;?>
    <?php else:?>
        <h5>Aún no se han subido ficheros</h5>
    <?php endif ?>
    </div>
</section>

<?php $i = 0; ?>
        <?php foreach($files as $file):?>

            <div id="modalImage<?= $i ?>" class="modal fade" tabindex="-1" aria-labelledby="modalImage<?= $i ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <img src="<?= Uri::root() ?><?= $path .$file ?>" alt="galeria" />
                    </div>
                </div>
            </div>
            <?php $i++; ?>
        <?php endforeach;?>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>