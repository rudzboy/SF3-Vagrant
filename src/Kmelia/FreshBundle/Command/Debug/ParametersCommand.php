<?php

namespace Kmelia\FreshBundle\Command\Debug;

use AppBundle\Command\AbstractKmeliaCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ParametersCommand extends AbstractKmeliaCommand
{
    const
        CONSOLE_WIDTH            = 70,
        SHOW_HEADER_AFTER_N_ROWS = 20;
    
    private
        $input,
        $output;
    
    protected function configure()
    {
        $this
            ->enableLocking()
            ->setName('kmelia:debug:parameters')
            ->setDescription('Debug the parameters per environment without launching it!')
            ->addArgument('environments', InputArgument::REQUIRED, 'List separate by comma')
            ->addOption('filter', 'f', InputOption::VALUE_OPTIONAL, 'Filter the parameters with this regexp')
            ->addOption('expand', 'x', InputOption::VALUE_NONE, 'Expand all the truncated values');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environments = explode(',', $input->getArgument('environments'));
        
        // initialize
        $isTruncated  = false;
        $headers      = ['env', 'root node', 'key (node)', 'value'];
        $numberOfRows = 0;
        
        $table = (new Table($output))
            ->setHeaders($headers);
        
        foreach ($environments as $environmentKey => $environmentName) {
            if ($environmentKey > 0) {
                $table->addRow(new TableSeparator());
                
                if ($numberOfRows > self::SHOW_HEADER_AFTER_N_ROWS) {
                    $table->addRow($headers);
                    $table->addRow(new TableSeparator());
                }
            }
            
            // reset for each environment
            $numberOfRows = 0;
            
            // kernel and container
            $environmentKernel = new \AppKernel($environmentName, false);
            $environmentKernel->initializeWithoutCaching();
            $environmentContainer = $environmentKernel->getContainer();
            
            // symfony parameters
            $configurations = [
                'parameters' => $environmentContainer->getParameterBag()->all(),
            ];
            
            // bundle configurations
            foreach ($environmentKernel->getBundles() as $bundle) {
                $extension = $bundle->getContainerExtension();
                
                if ($extension instanceof ExtensionInterface) {
                    $configs       = $environmentContainer->getExtensionConfig($extension->getAlias());
                    $configuration = $extension->getConfiguration($configs, $environmentContainer);
                    
                    if ($configuration instanceof ConfigurationInterface) {
                        $configurations[$extension->getAlias()] = (new Processor())
                            ->processConfiguration($configuration, $environmentContainer->getParameterBag()->resolveValue($configs));
                    }
                }
            }
            
            foreach ($configurations as $rootNode => $nodeConfigurations) {
                foreach ($nodeConfigurations as $key => $value) {
                    if ($input->getOption('filter') !== null) {
                        $pattern = sprintf(
                            '~%s~',
                            $input->getOption('filter')
                        );
                        
                        if (!preg_match($pattern, $key) && !preg_match($pattern, $rootNode)) {
                            continue;
                        }
                    }
                    
                    $value = $this->renderValue($value);
                    
                    if ($input->getOption('expand') === false && strlen($value) >= self::CONSOLE_WIDTH) {
                        $showMoreMessage = ' [truncated]';
                        
                        $value = sprintf(
                            '%s%s',
                            substr(substr($value, 0, self::CONSOLE_WIDTH), 0, - strlen($showMoreMessage)),
                            $showMoreMessage
                        );
                        
                        $isTruncated = true;
                    }
                    
                    $table->addRow([$environmentName, $rootNode, $key, $value]);
                    $numberOfRows++;
                }
            }
        }
        
        if ($isTruncated) {
            $table->setColumnWidths([0, 0, 0, self::CONSOLE_WIDTH]);
        }
        
        $table->render();
    }
    
    private function renderValue($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        
        if (is_bool($value)) {
            return $this->renderBoolValue($value);
        }
        
        if (is_null($value)) {
            return 'NULL  (null)';
        }
        
        return $value;
    }
    
    private function renderBoolValue($value)
    {
        $stringValue = 'false';
        if ($value === true) {
            $stringValue = 'true ';
        }
        
        return sprintf(
            '%s (boolean)',
            $stringValue
        );
    }
}
