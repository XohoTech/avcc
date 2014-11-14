<?php

namespace Application\Bundle\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\FrontBundle\Helper\DefaultFields;
use Application\Bundle\FrontBundle\Entity\Records;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Records controller.
 * 
 * @Route("/")
 *
 */
class RecordsController extends Controller
{

    private $columnOrder = array();
    private $defaultFields;
    private $columns = array();
    private $userFields = array();
    private $session ;
    private $offset ;
    private $limit ;
    
    
    public function __construct()
    {
        $this->columns = array(
            'checkbox_Col' => 'checkboxCol',
            'Project_Name' => 'projectName',
            'Unique_ID' => 'uniqueId',
            'Title' => 'title',
            'Collection_Name' => 'collectionName',
            'Location' => 'location'
        );
        $this->session = new Session();
        $this->defaultFields = new DefaultFields();
        $this->limit = 10;
    }

    /**
     * Lists all AudioRecords entities.
     *
     * @Route("/", name="record_list")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:AudioRecords:index.html.twig")
     * @return array
     */
    public function indexAction(Request $request)
    {
        $offSet = 0;
        
        $this->session->set('offset', $offSet);
        $this->offset = $this->session->get('offset');
        $em = $this->getDoctrine()->getManager();
        $column = $this->columns;
//        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAll();
        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAllRecords($offSet, $this->limit);
        $data = $this->getData($entities);
        
        return array(
            'data' => $data,
            'columns' => $column
        );
    }

    /**
     * Make records to display for dataTables.
     * 
     * @param Request $request 
     * 
     * @Route("/dataTable", name="record_dataTable")
     * @Method("GET")
     * @Template("ApplicationFrontBundle:AudioRecords:dataTable.html.php") 
     * @return json
     */
    public function dataTableAction(Request $request)
    {
        $sEcho = $request->query->get('sEcho');
        $sSortDir_0 = $request->query->get('sSortDir_0');
        $iSortCol_0 = $request->query->get('iSortCol_0');
        
        $offSet = $request->query->get('iDisplayStart') ? $request->query->get('iDisplayStart') : 0;
        
        $em = $this->getDoctrine()->getManager();
        $column = $this->columns;
        $this->session->remove('column');
        $this->session->remove('jscolumn');
        $this->session->remove('columnOrder');
        $this->session->set('jscolumn', $iSortCol_0);
        $this->session->set('columnOrder', $sSortDir_0);
        
        foreach($column as $key => $value){
            $columnOrder[] = array("title" => $key, "field" => $value, "hidden" => 0);
        }
        $this->session->set('column', $column[$columnOrder[$iSortCol_0]['title']]);   
        $col = $this->session->get('column');
        $order = $this->session->get('columnOrder');
        
//        $records = $this->sphinx->carrier_list($offset, 100, TRUE);
        $entities = $em->getRepository('ApplicationFrontBundle:Records')->findAllRecords($offSet, $this->limit, $col, $order);
        $data = $this->getData($entities);
        $tableView = $this->defaultFields->recordDatatableView($entities, $columnOrder);

        $dataTable = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => intval($data['count']),
            'iTotalDisplayRecords' => intval($data['count']),
            'aaData' => $tableView
        );
        echo json_encode($dataTable);
        exit;
    }

    
    private function getData($entities)
    {
        $data = array();
        $data['total'] = count($entities);    
        $data['records'] = $entities;
        $data['count'] = count($entities);
        if ($data['count'] > 0 && $this->offset === 0)
        {
            $data['start'] = 1;
            $data['end'] = $data['count'];
        }
        else
        {
            $data['start'] = $this->offset;
            $data['end'] = intval($this->offset) + intval($data['count']);
        }
        
        return $data;
    }
}
