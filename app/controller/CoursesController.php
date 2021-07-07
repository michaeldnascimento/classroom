<?php

namespace app\controller;

use app\core\Controller;
use app\model\CoursesModel;
use app\classes\Input;

class CoursesController extends Controller
{

    //Instância da classe ProdutoModel
    private $coursesModel;

    /**
     * Método construtor
     *
     *
     */
    public function __construct()
    {
        $this->coursesModel = new CoursesModel();
    }

    /**
     * Carrega a página principal
     *
     * @return void
     */
    public function index()
    {
        $this->load('cursos/main');
    }

    /**
     * Carrega a página com a lista curso
     *
     * @return void
     */
    public function lista()
    {
        $result = $this->coursesModel->listCourses();

        $this->load('cursos/lista', [
            'consultList' => $result
        ]);
    }

    public function consultCourses()
    {
        $resultConsult = $this->coursesModel->consultCourse();


        $this->load('cursos/consulta-curso', [
            'returnCourses' => $resultConsult
        ]);
    }


    public function addProfessor()
    {
        $resultAddProfessor = $this->coursesModel->addProfessor();

        $this->load('cursos/add-professor', [
            'returnAddProfessor' => $resultAddProfessor
        ]);
    }

    public function addAluno()
    {
        $resultAddStudent = $this->coursesModel->addStudent();

        $this->load('cursos/add-aluno', [
            'resultAddStudent' => $resultAddStudent
        ]);
    }

    public function insert()
    {
        $produto = $this->getInput();

        if (!$this->validate($produto, false)) {
            return  $this->showMessage(
                'Formulário inválido', 
                'Os dados fornecidos são inválidos',
                BASE  . 'novo-produto/',
                422
            );
        }

        $result = $this->produtoModel->insert($produto);

        if ($result <= 0) {
            echo 'Erro ao cadastrar um novo produto';
            die();
        }

        redirect(BASE . 'editar-produto/' . $result);
    }

    /**
     * Realiza a busca na base de dados e exibe na página de resultados
     *
     * @return void
     */
    public function pesquisar()
    {
        $param = Input::get('pes');

        $this->load('produto/pesquisa', [
            'termo' => $param
        ]);
    }

    /**
     * Retorna os dados do formulário em uma classe padrão stdObject
     *
     * @return object
     */
    private function getInput()
    {

        return (object)[
            'id'        => Input::get('id', FILTER_SANITIZE_NUMBER_INT),
            'nome'      => Input::post('txtNome'),
            'imagem'    => Input::post('txtImagem'),
            'descricao' => Input::post('txtDescricao')
        ];
    }

    /**
     * Valida se os campos recebidos estão válidos
     *
     * @param  Object $produto
     * @param  bool $validateId
     * @return bool
     */
    private function validate($produto, $validateId = true)
    {
        if ($validateId && $produto->id <= 0)
            return false;

        if (strlen($produto->nome) < 3)
            return false;

        if (strlen($produto->imagem) < 5)
            return false;

        if (strlen($produto->descricao) < 10)
            return false;

        return true;
    }
}
