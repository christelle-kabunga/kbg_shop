<?php
    /**
     * Cette fonction nous aide à recuperer la photo, la deplace, et renvoyer son nom à la base
     * de données, lors que l'extention de la photo selectionnée n'est pas recommander, ça
     * retourne 0
     * Si non, ça retourne le nom et extention de la photo ou fichier
     */
    function RecuperPhoto($image, $file, $destination)
    {
        $filetmp=$file['tmp_name'];
        $fileext = explode(('.'), $image);
        $fileckek = strtolower(end($fileext));
        $fileextsrom = array( 'png',  'jpg', 'jpeg');
        if(empty($image)) {
            print -1;
        } elseif (!in_array($fileckek, $fileextsrom)) {
            return '0';
        }else{
            move_uploaded_file($filetmp, $destination);
            return $image;
        }
    }

    /**
     * Cette fonction recuper les dernier carractere dans un string
     * Cela prénd les string à deduire et le nombre de carractère à deduire
     */
    function getLastCharacters($string, $num) {
        return substr($string, -$num);
    }
