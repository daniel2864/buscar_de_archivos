<br><br>
<form action="<?php echo obtenerRuta() ?>" method="get">
    <input type="text" name="nombre_archivo" value="<?php echo (empty($_GET["nombre_archivo"]) ? '' : $_GET["nombre_archivo"]) ?>" placeholder="nombre archivo">
    <input type="submit" value="Enviar">
</form>
<hr>
<?php

function search_files($dir, &$files, $archivo_buscar)
{
    if (is_dir($dir)) {
        if ($gd = opendir($dir)) {
            while (($file = readdir($gd)) !== false) {
                if ($file != '.' and $file != '..') {
                    if (is_dir($dir . '/' . $file)) {
                        search_files($dir . '/' . $file, $files, $archivo_buscar);
                    } else {
                        if (is_file($dir . '/' . $file)) {
                            if (verifica_nombre_archivo($file, $archivo_buscar)) {
                                //$files[dirname($dir . '/' . $file) . "/" . $file] = filemtime($dir . '/' . $file);
                                $files[] = dirname($dir . '/' . $file) . "/" . $file;
                            }
                        }
                    }
                }
            }
            closedir($gd);
        }
    }
}

function verifica_nombre_archivo($cadena, $value)
{
    if (strlen(stristr($cadena, $value)) > 0) {
        return true;
    }
    return false;
}


function obtenerRuta()
{
    $host = $_SERVER["HTTP_HOST"];
    $url = $_SERVER["REQUEST_URI"];
    if (verifica_nombre_archivo($url, '?')) {
        $u = explode('?', $url);
        return "http://" . $host . $u[0];
    }
    return "http://" . $host . $url;
}


if (!empty($_GET['nombre_archivo'])) {

    $archivo_buscar = $_GET['nombre_archivo']; 
    $ruta           = '/Applications/MAMP/htdocs/consulta/';
    $files          = array();

    search_files($ruta, $files, $archivo_buscar);

    foreach ($files as   $value) {
        echo $value . '<br>';
    }

}
