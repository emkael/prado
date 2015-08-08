<?php

/**
 * @author Daniel Sampedro Bello <darthdaniel85@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @since 3.3
 * @package Prado\Wsat
 */

namespace Prado\Wsat;

use Prado\Prado;


class TWsatScaffoldingGenerator extends TWsatBaseGenerator
{
        /**
         * Const View Types for generation
         */

        const LIST_TYPE = 0;
        const ADD_TYPE = 1;
        const SHOW_TYPE = 2;

        /**
         * Bootstrap option
         */
        private $_bootstrap;

        function __construct()
        {
                parent::__construct();
        }

        public function generate()
        {
                $unitName = "Example";
                $class = $this->generateClass($unitName);
                $outputClass = $this->_opFile . DIRECTORY_SEPARATOR . $unitName . ".php";
                file_put_contents($outputClass, $class);

                $outputPage = $this->_opFile . DIRECTORY_SEPARATOR . $unitName . ".page";
                $page = $this->generatePage("student", self::ADD_TYPE);
                file_put_contents($outputPage, $page);
        }

        // <editor-fold defaultstate="collapsed" desc="Page Generation">
        private function generatePage($tableName, $type, $tContentId = "Content")
        {
                $pageContent = $this->getPageContent($tableName, $type);
                return <<<EOD
<com:TContent ID="$tContentId">   
                     
       $pageContent
               
</com:TContent>
EOD;
        }

        private function getPageContent($tableName, $type)
        {
                $code = "";
                $tableInfo = $this->_dbMetaData->getTableInfo($tableName);
                switch ($type)
                {
                        case self::LIST_TYPE:
                                break;
                        case self::ADD_TYPE:
                                foreach ($tableInfo->getColumns() as $colField => $colMetadata)
                                {
                                        if (!$colMetadata->IsPrimaryKey && !$colMetadata->IsForeignKey)
                                        {
                                                $code .= $this->generateControl($colMetadata);
                                                $code .= $this->generateValidators($colMetadata);
                                                $code .= "\n";
                                        }
                                }
                                foreach ($tableInfo->getForeignKeys() as $colField => $colMetadata)
                                {
                                        $colField = '"' . $colMetadata["table"] . '"';
                                        $code .= "\t<com:TTextBox ID=$colField />\n";
                                        $code .= "\n";
                                        //  TWsatBaseGenerator::pr($tableInfo);
                                }

                        case self::SHOW_TYPE:
                                break;
                }
                return $code;
        }

        private function generateControl($colMetadata)
        {
                $controlType = "TTextBox";
                switch ($colMetadata->DbType){
                        
                }
                $controlId = $colMetadata->ColumnId;
                return "\t<com:$controlType ID=\"$controlId\" />\n";
        }

        private function generateValidators($colMetadata)
        {
                $controlId = $colMetadata->ColumnId;
                $code = "";
                if (!$colMetadata->AllowNull)
                {
                        $code .= "\t<com:TRequiredFieldValidator ControlToValidate=$controlId ValidationGroup=\"addGroup\" Text=\"Field $controlId is required.\" Display=\"Dynamic\" />\n";
                }
                return $code;
        }

// </editor-fold>
        //---------------------------------------------------------------------
        // <editor-fold defaultstate="collapsed" desc="Code Behind Generation">
        private function generateClass($classname)
        {
                $date = date('Y-m-d h:i:s');
                $env_user = getenv("username");
                return <<<EOD
<?php
/**
 * Auto generated by PRADO - WSAT on $date.
 * @author $env_user
 */
class $classname extends TPage
{

}
EOD;
        }

// </editor-fold>
}
