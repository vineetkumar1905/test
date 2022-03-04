<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
  
class FullCalenderController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
  
        if($request->ajax()) {
            $data = DB::table('events')->whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['id', 'title', 'start', 'end']);

             // $data = Event::whereDate('start', '>=', $request->start)
             //           ->whereDate('end',   '<=', $request->end)
             //           ->get(['id', 'title', 'start', 'end']);
  
             return response()->json($data);
        }
  
        return view('fullcalender');
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
 
        switch ($request->type) {
           case 'add':
           $event = DB::table('events')->insert([          
              //$event = Event::create([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($event);
             break;

            case 'update':
            //print_r($request->all());
            //$event = DB::table('events')->where('id',$request->id)->update([          
              $event = Event::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
            //print_r($event); 
              return response()->json($event);
             break;
  
           case 'move':
           $event = DB::table('events')->where('id',$request->id)->update([ 
              //$event = Event::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
           $event = DB::table('events')->where('id',$request->id)->delete(); 
             // $event = Event::find($request->id)->delete();  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}