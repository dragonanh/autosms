<?php
  use Box\Spout\Common\Type;
  use Box\Spout\Writer\WriterFactory;
  use Box\Spout\Writer\Style\StyleBuilder;
  use Box\Spout\Writer\Style\Color;

  class spoutHelper{
    private $reportWriter;

    public function __construct($outputFilePath)
    {
      $this->reportWriter = WriterFactory::create(Type::XLSX);
      $this->reportWriter->openToFile($outputFilePath);
    }

    public function writeRow($row)
    {
      $this->reportWriter->addRow($row);
    }

    public function writeHeaderRow($headerRow)
    {
      $headerStyle = (new StyleBuilder())->setFontBold()->build();
      $this->reportWriter->addRowWithStyle($headerRow, $headerStyle);
    }

    public function close()
    {
      $this->reportWriter->close();
    }
  }