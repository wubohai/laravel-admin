<?php

namespace App\Http\Controllers\Admin;

use App\Models\Keywords;
use Illuminate\Http\Request;
use App\Models\KeywordsType;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeywordsTypeResource;

class KeywordsController extends Controller
{
    public function index(Request $request)
    {
        $query = Keywords::query()->with('type');

        if ($request->filled('key')) {
            $name = $request->input('key');
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%'.$name.'%');
                $query->orWhere('key', 'like', '%'.$name.'%');
            });
        }

        $type = null;
        if ($request->filled('type')) {
            $query_type = $request->input('type');
            $query->where('type_id', $query_type);
            $type = KeywordsTypeResource::make(KeywordsType::find($query_type));
        }

        $list = $query->paginate();

        return view('admin.keywords.index', compact('list', 'type'));
    }

    public function create(Request $request)
    {
        $type = null;
        if ($request->filled('type')) {
            $type = KeywordsTypeResource::make(KeywordsType::findOrFail($request->input('type')));
        }
        return view('admin.keywords.create', compact('type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'name' => 'required',
            'type_id' => 'required'
        ]);
        $entity = new Keywords();
        $entity->type_id = $request->input('type_id');
        $entity->type_key = KeywordsType::findOrFail($entity->type_id)->key;
        $entity->key = $request->input('key');
        $entity->name = $request->input('name');
        $entity->save();

        return redirect(route('admin.keywords.index'))->with('flash_message', '添加成功');
    }

    public function edit($id)
    {
        $entity = Keywords::findOrFail($id);

        $type = KeywordsTypeResource::make($entity->type);

        return view('admin.keywords.edit', compact('entity', 'type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'name' => 'required',
            'type_id' => 'required',
        ]);

        $entity = Keywords::findOrFail($id);
        $entity->type_id = $request->input('type_id');
        $entity->type_key = KeywordsType::findOrFail($entity->type_id)->key;
        $entity->key = $request->input('key');
        $entity->name = $request->input('name');

        $entity->save();

        return redirect(route('admin.keywords.index'))->with('flash_message', '修改成功');
    }

    public function destroy($id)
    {
        $type = Keywords::findOrFail($id);

        $type->delete();

        return redirect(route('admin.keywords.index'))->with('flash_message', '删除成功');
    }
}
