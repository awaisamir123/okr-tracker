<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OKR Tracker</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Fontawesome icons -->
        <link href="{{asset('public/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
        <link href="{{asset('public/fontawesome/css/brands.css') }}" rel="stylesheet">
        <link href="{{asset('public/fontawesome/css/solid.css') }}" rel="stylesheet">
        
        <!-- Styles -->
        <link href="{{asset('public/css/style.css') }}" rel="stylesheet">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-5">


            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

                <div class="container mt-5">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3 status-tab m-auto">
                            <form action="{{route('index')}}" method="get">
                                <label for="role">Select Role</label>
                                <div class="select">
                                    <select name="role" id="role" onchange="this.form.submit()">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role->id}}" {{(session('currentRole') && session('currentRole') == $role->id?'selected':'')}}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="container mt-5">
                    <div class="row">
                    @if(session('currentRole'))
                        @foreach($statuses as $status)
                        <div class="col-sm-12 col-md-3 col-lg-3 status-tab">
                            <div class="main-area droppable section-{{$status->id}}" status_id="{{$status->id}}">
                                <h5>{{$status->name}}
                                @if($status->id == '1' && session('currentRole') == '1')
                                <a href="#" class="color-white float-right" id="addTaskBtn" data-toggle="modal" data-target="#basicModal"><i class="fas fa-plus"></i></a>
                                @endif
                                </h5>
                                @if(count($status->tasks))
                                    @foreach($status->tasks as $task)
                                        <div class="draggable task-tab mt-2" task_id="{{$task->id}}" status_id="{{$task->status_id}}" task="{{$task}}">
                                            <h6>{{$task->name}}</h6>
                                            <p>{{$task->description}}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-task-tab task-tab text-center mt-2">
                                        <p>No Tasks</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-sm-12 col-md-12 col-lg-12 status-tab">
                            <div class="main-area">
                                <div class="task-tab text-center mt-2">
                                    <p>Please select role to access tasks.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>


        </div>

        <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    
        <div class="modal-dialog modal-lg">
        
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Add Task</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            
            <div class="modal-body">

                <form action="" id="taskForm">
                <input type="hidden" name="id" value="0">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description"  class="form-control" id="description" cols="30" rows="10" placeholder="Description"></textarea>
                </div>
                </form>

            </div>
            
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="taskFormSubmit">Save changes</button>
            </div>
        </div>
        </div>
  </div>

  <script>
  $( function() {
    $( ".draggable" ).draggable({
        revert: "invalid",
    });
    $( ".droppable" ).droppable({
      drop: function( event, ui ) {
        var task = $(ui.draggable).attr('task');
        var status_id = $(ui.draggable).attr('status_id');
        var task_id = $(ui.draggable).attr('task_id');
        var remainingItems = $(ui.draggable).siblings('.task-tab').length;
        var draggableParent = $(ui.draggable).parent('.main-area');
        var newElement = $(this).append("<div class='draggable task-tab mt-2 ui-draggable ui-draggable-handle' task_id='"+task_id+"' status_id='"+status_id+"' task='"+task+"'>"  + $(ui.draggable).html() + "</div>");
        $(ui.draggable).remove();
        if(remainingItems == 0){
            draggableParent.append('<div class="no-task-tab task-tab text-center mt-2"><p>No Tasks</p></div>')
        }
        $(this).find('.no-task-tab').remove();
        $('.draggable').draggable({
            revert: "invalid"
        });
        var newStatus = $(this).attr('status_id');
        updateStatus(task_id,newStatus);
      }
    }); 
  });

  function updateStatus(task_id,status_id){
    $.ajax({
            url: '{{route("task.update")}}',
            type: 'GET',
            data: {
                _token: "{{ csrf_token() }}",
                task_id: task_id,
                status_id: status_id,
            },
            dataType: 'json',
            success: function (response) {
                processing = false;
                console.log(response.content);
                if(response.status == 1){

                }else{
                }
            },
            error: function (jqXHR) {

            }
        });
  }

  </script>
  <script>
      $(document).on('click', '#taskFormSubmit', function(){
        
        var id = $("#taskForm input[name='id']").val();
        var name = $("#taskForm input[name='name']").val();
        var description = $("#taskForm #description").val();

        var newTask = "<div class='draggable task-tab mt-2 ui-draggable ui-draggable-handle' task_id='' status_id='1' task=''><h6>"+name+"</h6><p>"+description+"</p></div>";
        $('.section-1').append(newTask);

        $.ajax({
            url: '{{route("task.store")}}',
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                name: name,
                description: description,
            },
            dataType: 'json',
            success: function (response) {
                processing = false;
                console.log(response.content);
                if(response.status == 1){
                    $("#basicModal").modal('hide');
                    var id = $("#taskForm input[name='id']").val('0');
                    var name = $("#taskForm input[name='name']").val('');
                    var description = $("#taskForm #description").val('');
                    $('.modal-title').html('Add Task');
                    $('.section-1').children('.draggable').last().attr('task_id', response.content.id);
                    $('.section-1').children('.draggable').last().attr('task', response.content);

                    $( ".draggable" ).draggable({
                        revert: "invalid",
                    });

                }else{
                    $('.section-1').children('.draggable').last().remove();
                }
            },
            error: function (jqXHR) {

            }
        });
      })
  </script>
  <script>
      $(document).on('click', '.editBtn', function() {
            var task = JSON.parse($(this).parent('h6').parent('.task-tab').attr('task'));
            var id = $("#taskForm input[name='id']").val(task.id);
            var name = $("#taskForm input[name='name']").val(task.name);
            var description = $("#taskForm #description").val(task.description);
            $('.modal-title').html('Edit Task');
            $("#basicModal").modal('show');
      });
      $(document).on('click', '#addTaskBtn', function() {
            var id = $("#taskForm input[name='id']").val('0');
            var name = $("#taskForm input[name='name']").val('');
            var description = $("#taskForm #description").val('');
            $('.modal-title').html('Add Task');

      });
      
  </script>
    </body>
</html>
