<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
// use App\User;
  
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        
        return $dataTable->render('users');
        
    }
}