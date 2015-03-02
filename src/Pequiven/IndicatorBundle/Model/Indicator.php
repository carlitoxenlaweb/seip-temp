<?php

namespace Pequiven\IndicatorBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo del indicador
 *
 * @author matias
 */
abstract class Indicator implements IndicatorInterface
{
    /**
     * Tipo de calculo por formula y valores de las variables manuales
     */
    const TYPE_CALCULATION_FORMULA_MANUALLY = 0;
    
    /**
     * Tipo de calculo por formula y valores de las variables automaticos
     */
    const TYPE_CALCULATION_FORMULA_AUTOMATIC = 1;
    
    /**
     * Indicador con fórmula asociada
     */
    const INDICATOR_WITH_FORMULA = 'INDICATOR_WITH_FORMULA';
    
    /**
     * Indicador sin fórmula asociada
     */
    const INDICATOR_WITHOUT_FORMULA = 'INDICATOR_WITHOUT_FORMULA';
    
    /**
     * Indicador con resultado
     */
    const INDICATOR_WITH_RESULT = 'INDICATOR_WITH_RESULT';
    
    /**
     * Indicador sin resultado
     */
    const INDICATOR_WITHOUT_RESULT = 'INDICATOR_WITHOUT_RESULT';
    
    const TYPE_OBJECT = 'indicator';
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="typeOfCalculation", type="integer", nullable=false)
     */
    protected $typeOfCalculation = self::TYPE_CALCULATION_FORMULA_MANUALLY;
    
    /**
     * IndicatorLevel
     * @var \Pequiven\IndicatorBundle\Entity\IndicatorLevel
     * @ORM\ManyToOne(targetEntity="\Pequiven\IndicatorBundle\Entity\IndicatorLevel")
     * @ORM\JoinColumn(name="fk_indicator_level", referencedColumnName="id",nullable = false)
     */
    protected $indicatorLevel;
    
    /**
     * Etiqueta del tipo de resumen de los indicadores
     * @var type 
     */
    protected $labelSummary = '';
    
    /**
     * Set indicatorLevel
     *
     * @param \Pequiven\IndicatorBundle\Entity\IndicatorLevel $indicatorLevel
     * @return Indicator
     */
    public function setIndicatorLevel(\Pequiven\IndicatorBundle\Entity\IndicatorLevel $indicatorLevel)
    {
        $this->indicatorLevel = $indicatorLevel;

        return $this;
    }

    /**
     * Get indicatorLevel
     *
     * @return \Pequiven\IndicatorBundle\Entity\IndicatorLevel 
     */
    public function getIndicatorLevel()
    {
        return $this->indicatorLevel;
    }
    
    /**
     * Retorna el tipo de calculo del indicador
     * @return integer
     */
    function getTypeOfCalculation() 
    {
        return $this->typeOfCalculation;
    }
    
    /**
     * Establece el tipo de calculo del indicador
     * 
     * @param integer $typeOfCalculation Indicator::TYPE_CALCULATION_*
     * @return \Pequiven\IndicatorBundle\Model\Indicator
     */
    function setTypeOfCalculation($typeOfCalculation) {
        $this->typeOfCalculation = $typeOfCalculation;
        
        return $this;
    }
    
    /**
     * Retorna los tipos de calculo disponible de los indicadores y sus etiquetas.
     * 
     * @staticvar array $typesOfCalculation
     * @return array
     */
    static function getTypesOfCalculation()
    {
        static $typesOfCalculation = array(
            self::TYPE_CALCULATION_FORMULA_MANUALLY => 'pequiven_indicator.type_calculation.formula_manually',
            self::TYPE_CALCULATION_FORMULA_AUTOMATIC => 'pequiven_indicator.type_calculation.formula_automatic',
        );
        return $typesOfCalculation;
    }
    
    function getTypeOfCalculationLabel() {
        $typesOfCalculation = self::getTypesOfCalculation();
        if(isset($typesOfCalculation[$this->typeOfCalculation]) === false){
            throw new Exception(sprintf('The type of calculation "%s" dont exist',$this->typeOfCalculation));
        }
        return $typesOfCalculation[$this->typeOfCalculation];
    }
    
    public function hasNotification()
    {
        if(count($this->getValuesIndicator()) > 0){
            return true;
        }
        return false;
    }
    
    /**
     * Retorna las etiquetas definidas para los tipos de resumen
     * 
     * @staticvar array $labelsStatus
     * @return string
     */
    static function getLabelsSummary()
    {
        static $labelsStatus = array(
            self::INDICATOR_WITH_FORMULA => 'pequiven_indicator.summary.with_formula',
            self::INDICATOR_WITHOUT_FORMULA => 'pequiven_indicator.summary.without_formula',
            self::INDICATOR_WITH_RESULT => 'pequiven_indicator.summary.with_result',
            self::INDICATOR_WITHOUT_RESULT => 'pequiven_indicator.summary.without_result',
        );
        return $labelsStatus;
    }
    
    /**
     * Retorna la etiqueta que corresponde a un estatus del programa de gestion
     * @return string
     */
    function getLabelSummary()
    {
        $labels = $this->getLabelsSummary();
        if(isset($labels[$this->labelSummary])){
            return $labels[$this->labelSummary];
        }
    }
    
    /**
     * 
     * @param \Pequiven\MasterBundle\Entity\Formula\Variable $variable
     * @return \Pequiven\MasterBundle\Entity\Formula\FormulaDetail
     */
    function getFormulaDetailByVariable(\Pequiven\MasterBundle\Entity\Formula\Variable $variable)
    {
        $result = null;
        foreach ($this->getFormulaDetails() as $formulaDetail)
        {
            if($formulaDetail->getVariable() === $variable)
            {
                $result = $formulaDetail;
                break;
            }
        }
        return $result;
    }
}
