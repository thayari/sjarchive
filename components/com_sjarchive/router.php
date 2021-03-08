<?php

// No direct access
defined('_JEXEC') or die;

/**
 * Class LayadvisoryRouter
 *
 * @since  3.3
 */
JLoader::registerPrefix('sjarchive', JPATH_SITE . '/components/com_sjarchive/');

class sjarchiveRouter extends JComponentRouterBase
{
    public function __construct()

    { }
    /**
     * Build method for URLs
     * This method is meant to transform the query parameters into a more human
     * readable form. It is only executed when SEF mode is switched on.
     *
     * @param   array  &$query  An array of URL arguments
     *
     * @return  array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since   3.3
     */
    public function build(&$query)
    {

        $segments = array();

        if (isset($query['controller'])) {
            $segments[] .= '/' . $query['controller'];
            unset($query['controller']);
        }

        if (isset($query['task'])) {
            $segments[] .= '/' . $query['task'];
            unset($query['task']);
        }

        if (isset($query['year'])) {
            $segments[] .= '/' . $query['year'];
            unset($query['year']);
        }

        if (isset($query['num'])) {
            $segments[] .= '/' . $query['num'];
            unset($query['num']);
        }


        if (isset($query['pages'])) {
            $segments[] .= '/' . $query['pages'];
            unset($query['pages']);
        }
        if (isset($query['ftype'])) {
            $segments[] .= '/' . $query['ftype'];
            unset($query['ftype']);
        }

        if (isset($query['feed'])) {
            $segments[] .= '/' . $query['feed'];
            unset($query['feed']);
        }


        return $segments;
    }

    /**
     * Parse method for URLs
     * This method is meant to transform the human readable URL back into
     * query parameters. It is only executed when SEF mode is switched on.
     *
     * @param   array  &$segments  The segments of the URL to parse.
     *
     * @return  array  The URL attributes to be used by the application.
     *
     * @since   3.3
     */
    public function parse(&$segments)
    {
        $vars = array();
        $vars['controller'] = $segments[0];
    	
    	if (isset($segments[1])) {
        $vars['task'] =  $segments[1];
        switch ($vars['task']) {
            case 'issue.display':
                $vars['year'] = $segments[2];
                $vars['num'] = $segments[3];
                break;
            case 'issue.download':
                $vars['year'] = $segments[2];
                $vars['num'] = $segments[3];
                $vars['ftype'] = $segments[4];                

                break;
            case 'issue.feed':
                $vars['feed'] = $segments[2];
               break;
            case 'article.display':
                $vars['year'] = $segments[2];
                $vars['num'] = $segments[3];
                $vars['pages'] = $segments[4];
                break;
            case 'article.download':
                $vars['year'] = $segments[2];
                $vars['num'] = $segments[3];
                $vars['pages'] = $segments[4];
                $vars['ftype'] = $segments[5];
                break;  
        }
    }

    return $vars;
}

}