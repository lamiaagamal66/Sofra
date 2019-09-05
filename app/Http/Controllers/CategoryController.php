<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Response;
 

class CategoryController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $categories = Category::paginate(20);
    return view('dashboard.categories.index',compact('categories'));
  }

  /**
   * Show the form for creating a new resource.1
   *
   * @return Response
   */
  public function create()
  {
    return view('dashboard.categories.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
      $this->validate($request,[
          'name' => 'required'
      ]);
      Category::create($request->all());
      flash()->success('Category added Successfully ..');
        return redirect(route('category.index'));


  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      $model = Category::findOrFail($id);
      return view('dashboard.categories.edit',compact('model'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id , Request $request)
  {
      $this->validate($request,[
          'name' => 'required',
      ]);
      $model = Category::findOrFail($id);
      $model->update($request->all());
        
      flash()->success('Successfully Updated');
      return redirect(route('category.index'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
      $category = Category::findOrFail($id);
      $count = $category->restaurants()->count();
      if ($count > 0)
      {
        flash()->error('Can not delete category, there are restaurants in it');
      }
      $category->delete();
      
      flash()->error('Successfully Deleted');
      return redirect(route('category.index'));
  }
  

}

?>