<?php

namespace Checkfood\CentralBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * CentralService 
 *
 * @author Safwen Toukabri <safwen.toukabri@proxym-it.com>
 */
class CentralService {

    public function setWsResult($data, $_format = 'json') {
        return $this->loadWS($data, $_format);
    }

    public function loadWS($data, $_format) {
        $encoders = array('json' => new JsonEncoder(), 'xml' => new XmlEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $response = new Response($serializer->serialize($data, $_format), 200, array('Content-type' => 'application/' . $_format));
        return $response;
    }

    public function getPaginationBar($path, $total, $items_per_page, $page = 1) {

        $nbrPages = ceil($total / $items_per_page);
        $delim = strpos($path, '?') ? '&page=' : '?page=';

        $pagination = '<div class="pagination">';
        $pagination .= '<ul>';

        $pagination .= '<li ' . ($page <= 1 ? 'class="disabled"' : '') . '><a href="' . ($page <= 1 ? 'javascript:void(0)' : $path . $delim . ($page == 1 ? 1 : 1)) . '"><< Début</a></li>';
        $pagination .= '<li ' . ($page <= 1 ? 'class="disabled"' : '') . '><a href="' . ($page <= 1 ? 'javascript:void(0)' : $path . $delim . ($page == 1 ? 1 : $page - 1)) . '">< Préc</a></li>';
        for ($i = $page; ($i <= $page + 10 && $i <= $nbrPages); $i++) {
            $pagination .= '<li ' . ($page == $i ? 'class="active"' : '') . '><a href="' . $path . $delim . $i . '">' . $i . '</a></li>';
        }
        $pagination .= '<li ' . ($page >= $nbrPages ? 'class="disabled"' : '') . '><a href="' . ($page >= $nbrPages ? 'javascript:void(0)' : $path . $delim . ($page == $nbrPages ? $nbrPages : $page + 1 )) . '">Suiv ></a></li>';
        $pagination .= '<li ' . ($page >= $nbrPages ? 'class="disabled"' : '') . '><a href="' . ($page >= $nbrPages ? 'javascript:void(0)' : $path . $delim . ($page == $nbrPages ? $nbrPages : $nbrPages )) . '">Fin >></a></li>';
        $pagination .= '</ul>';
        $pagination .= '</div>';

        return $pagination;
    }

    public function forbidden($admin = NULL, $RedirectRoot) {
        if (!$admin) {
            $outPut = "<h1>Accès interdit</h1>";
            $outPut .= "Vous n'êtes pas autorisé à accéder à cette page. Pour s'authentifier, <a href='" . $RedirectRoot . "'>veuillez cliquer sur ce lien</a>";
            echo $outPut;
            die;
        }
    }

    public function getBaseURL() {
        return 'http://' . $_SERVER['SERVER_NAME'] . '/';
    }

}
