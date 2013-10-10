<?php

namespace SensioLabs\Twig;

use Twig_Environment;
use Twig_SimpleFunction;

class CoreExtension extends \Twig_Extension_Core
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('range', 'range'),
            new \Twig_SimpleFunction('constant', 'twig_constant'),
            new \Twig_SimpleFunction('cycle', 'twig_cycle'),
            new \Twig_SimpleFunction('random', 'twig_random', array('needs_environment' => true)),
            new \Twig_SimpleFunction('date', 'twig_date_converter', array('needs_environment' => true)),
            //new \Twig_SimpleFunction('include', 'twig_include', array('needs_environment' => true, 'needs_context' => true, 'is_safe' => array('all'))),
            'include' => new \Twig_Function_Method($this, 'twigInclude', array(
                'needs_environment' => true,
                'needs_context'     => true,
                'is_safe'           => array('all')
            ))
        );
    }

    /**
     * Automatically searches one template in different folders
     * relying on a pre-defined template hiararchy
     *
     * All includes with special handling do not have
     * file extensions in their calls
     * so {{ include('header') }} will hit this special handling
     * and {{ include('foo.html.twig') }} will be ignored
     *
     * @return string
     */
    public function twigInclude(
        Twig_Environment $env,
        $context,
        $template,
        $variables = array(),
        $withContext = true,
        $ignoreMissing = false,
        $sandboxed = false
    ) {
        // example input: header
        // example output: ['template/header.template.twig', 'plugin/header.plugin.twig', 'default/header.default.twig']
        // output is aware of template hierarchy
        if (!is_array($template)) {

            // here comes the special patch according assigned template name to include
            if (strpos($template, '.') === false) {
                $name = $template;
                $template = array(
                    'template/' . $name . '.template.twig',
                    'plugin/' . $name . '.plugin.twig',
                    'default/' . $name . '.default.twig',
                );
            }
        }

        return twig_include($env, $context, $template, $variables, $withContext, $ignoreMissing, $sandboxed);
    }
}