<?php

namespace OroAcademic\Bundle\IssueBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issues_by_status/chart/{widget}",
     *      name="oroacademic_dashboard_issues_by_state_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OroAcademicIssueBundle:Dashboard:issuesByStatus.html.twig")
     */
    public function issuesByStatusAction($widget)
    {
        $items = $this->getDoctrine()
            ->getRepository('OroAcademicIssueBundle:Issue')
            ->getIssuesByStatus(
                $this->get('oro_security.acl_helper'),
                $this->get('oro_dashboard.widget_configs')
                    ->getWidgetOptions($this->get('request_stack')->getCurrentRequest()->query->get('_widgetId', null))
                    ->get('dateRange')
            );

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                array(
                    'name' => 'bar_chart',
                    'data_schema' => array(
                        'label' => array('field_name' => 'label'),
                        'value' => array(
                            'field_name' => 'issues'
                        )
                    ),
                )
            )
            ->getView();

        return $widgetAttr;
    }

    /**
     * @Route("/issues_widget_grid/{widget}",
     *      name="oroacademic_dashboard_issues_widget_grid",
     *      requirements={"widget"="[\w-]+"})
     * @Template("OroAcademicIssueBundle:Dashboard:widgetIssues.html.twig")
     *
     * @param $widget
     * @return array $widgetAttr
     */
    public function issueWidgetGridAction($widget)
    {
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['entity'] = $this->getUser();
        return $widgetAttr;
    }
}
