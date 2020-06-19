<?php

namespace FM\BbcodeBundle\Templating;

use FM\BbcodeBundle\Decoda\DecodaManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author    Al Ganiev <helios.ag@gmail.com>
 * @copyright 2012-2015 Al Ganiev
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class BbcodeExtension extends AbstractExtension
{
    /**
     * @var DecodaManager
     */
    protected $decodaManager;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(DecodaManager $decodaManager)
    {
        $this->decodaManager = $decodaManager;
    }

    /**
     * Strip tags.
     *
     * @param $value
     * @param $filterSet
     *
     * @return string
     *
     * @throws RuntimeError
     */
    public function clean($value, $filterSet = DecodaManager::DECODA_DEFAULT)
    {
        if (!is_string($value)) {
            throw new RuntimeError('The filter can be applied to strings only.');
        }

        return $this->decodaManager->get($value, $filterSet)->strip(true);
    }

    /**
     * @param $value
     * @param $filterSet
     *
     * @return string
     * @return \FM\BbcodeBundle\Decoda\Decoda
     *
     * @throws RuntimeError
     */
    public function filter($value, $filterSet = DecodaManager::DECODA_DEFAULT)
    {
        if (!is_string($value)) {
            throw new RuntimeError('The filter can be applied to strings only.');
        }

        return $this->decodaManager->get($value, $filterSet)->parse();
    }

    /**
     * (non-PHPdoc).
     *
     * @return array
     * @see AbstractExtension::getFilters()
     *
     */
    public function getFilters()
    {
        $options = ['is_safe' => ['html']];

        return [
            new TwigFilter('bbcode_filter', [$this, 'filter'], $options),
            new TwigFilter('bbcode_clean', [$this, 'clean'], $options),
        ];
    }

    /**
     * (non-PHPdoc).
     *
     * @see AbstractExtension::getName()
     */
    public function getName()
    {
        return 'fm_bbcode';
    }
}
