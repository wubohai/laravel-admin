<div class="collapse {{$pid==0?'show':''}}" id="collapseExample{{$pid}}">
    <ul class="list-group" style="margin-bottom: 0;padding-left: {{$pid==0?'':'3'}}rem;">
        @foreach($permissions as $permission)
            @if($permission->pid == $pid)
                <li class="list-group-item" data-toggle="collapse" data-target="#collapseExample{{$permission->id}}" aria-controls="collapseExample{{$permission->id}}">
                    @if($checked)
                        <input type="checkbox" name="menus[]" class="permission-checkbox" onclick="permissionCheckbox(this, true)" data-pid="{{$pid}}" {{$checked->contains($permission->id)?'checked':''}} value="{{$permission->id}}">
                    @endif
                        <i class="{{$permission->key}}"></i>
                        <a href="{{route('admin.menu.edit', $permission)}}" class="list-link">
                            {{$permission->name}}
                        </a>

                        @can('admin.menu.destroy')
                        <button type="submit" form="delForm{{$permission->id}}" class="btn btn-default btn-xs" title="删除" onclick="return confirm('是否确定？')"><i class="fa fa-trash-o"></i></button>
                        <form class="form-inline hide" id="delForm{{$permission->id}}" action="{{ route('admin.menu.destroy', $permission) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                        </form>
                        @endcan
                    
                    @if(!$permission->url)
                    <i class="fa fa-angle-left pull-right"></i>
                    @endif
                </li>
                @include('admin.menu.tree', ['permissions'=>$permissions, 'pid'=>$permission->id, 'checked' => $checked])
            @endif
        @endforeach
    </ul>
</div>