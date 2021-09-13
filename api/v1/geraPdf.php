<?php

  class geraPDF {

    var $arquivo = null;
    var $file = null;
    var $comando;
    var $header = null;
    var $footer = null;
    var $logo = null;

    function setArquivo($arquivo) {
      global $ROOT;
      $this->arquivo = $ROOT . "/" . $arquivo;
    }

    function geraLink($arquivo,$texto, $logo=null, $header=null, $footer=null) {
        $link = "gera-pdf.php?arquivo=$arquivo";
        if(!empty($logo)) $link .= "&logo=$logo";
        if(!empty($header)) $link .= "&header=$header";
        if(!empty($footer)) $link .= "&footer=$footer";

        echo "<a href='$link'>$texto</a>";
    }

    function setComando($comando) {
      $this->comando = $comando;
    }

    function criaPDF($html='') {

      global $ROOT;


      if(!empty($html)) {
        if(empty($this->arquivo)) {
            $this->arquivo = $ROOT . '/etc/documento';
        }
        $f = fopen($this->arquivo . '.html','w');
        fwrite($f,$html);
        fclose($f);
      }
      define('HTMLDOC', "/usr/local/htmldoc/bin/htmldoc");
      $outfile = sprintf('%s.pdf', $this->arquivo);
      $infile = sprintf('%s.html', $this->arquivo);

      if(empty($this->comando)) {
          if(empty($this->comando))$this->comando = '%s --webpage -f %s  --left 2cm --right 2cm --bottom 1cm --headfootsize 9 ';
          if(!empty($this->footer)) $this->comando .= ' --footer ' . $this->footer . ' %s ';
          if(empty($this->footer)) $this->comando .= ' --footer c/ %s ';
      }

      if(!empty($this->logo)) $this->comando .= ' --logoimage ' . $this->logo . " ";
      if(!empty($this->header)) $this->comando .= ' --header ' . $this->header . " ";

      $cmd = sprintf($this->comando, HTMLDOC, $outfile, $infile);
      @exec($cmd);

      header("Content-type: application/save");
      header('Content-Disposition: attachment; filename="' . basename($this->arquivo . ".pdf") . '"');
      header('Expires: 0');
      header('Pragma: no-cache');
      readfile($this->arquivo . ".pdf");
      unlink($this->arquivo . ".pdf");
      if(!empty($html)) {
          unlink($this->arquivo . ".html");
      }
    }

    function retornaPDF($html='') {

        require_once('../etc/config/machine.php');
        global $ROOT;

        if(!empty($html)) {
            if(empty($this->arquivo)) {
                $this->arquivo = $ROOT . '/etc/documento';
            }
            $f = fopen($this->arquivo . '.html','w');
            fwrite($f,$html);
            fclose($f);
        }
        define('HTMLDOC', "/usr/local/htmldoc/bin/htmldoc");
        $outfile = sprintf('%s.pdf', $this->arquivo);
        $infile = sprintf('%s.html', $this->arquivo);

        if(empty($this->comando)) {
            if(empty($this->comando))$this->comando = '%s --webpage -f %s  --left 2cm --right 2cm --bottom 1cm --headfootsize 9 ';
            if(!empty($this->footer)) $this->comando .= ' --footer ' . $this->footer . ' %s ';
            if(empty($this->footer)) $this->comando .= ' --footer c/ %s ';
        }

        if(!empty($this->logo)) $this->comando .= ' --logoimage ' . $this->logo . " ";
        if(!empty($this->header)) $this->comando .= ' --header ' . $this->header . " ";

        $cmd = sprintf($this->comando, HTMLDOC, $outfile, $infile);
        @exec($cmd);

        
        return $arquivo . ".pdf";
    }

    function setHeader($header) {
        $this->header = $header;
    }
    function setFooter($footer) {
        $this->footer = $footer;
    }
    function setLogo($logo) {
        $this->logo = $logo;
    }

  }

  function getRequest($var) {
     $retorna = (empty($_GET[$var]))?$_POST[$var]:$_GET[$var];
     return $retorna;
  }

  $arquivo = getRequest('arquivo');
  if(!empty($arquivo)) {

    $arq = getRequest('arquivo');
    $header = getRequest('header');
    $footer = getRequest('footer');
    $logo = getRequest('logo');

    $gPDF = new geraPDF;
    $gPDF->setArquivo($arq);

    if(!empty($header)) $gPDF->setHeader($header);
    if(!empty($footer)) $gPDF->setFooter($footer);
    if(!empty($logo)) $gPDF->setLogo($logo);
    $gPDF->criaPDF();
  }
?>