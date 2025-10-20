<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kanban - Template (Tailwind)</title>
  @vite(['resources/js/app.js','resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen p-8 font-sans">
  <div class="max-w-7xl mx-auto">
    <header class="mb-8 text-center">
      <h1 class="text-3xl font-bold text-gray-800">Kanban Board</h1>
      <p class="text-gray-500 mt-2">Preparando • Desenvolvendo • Pronto</p>
    </header>

    <main class="grid gap-6 grid-cols-1 md:grid-cols-3" x-data="{
      cols:[],
      createDataToUpdateKanban: function(tasks,columnId){
        this.cols[columnId] ??= {};
          tasks.forEach((task,key) => {
            let newPosition = key+1;
            task.setAttribute('x-sort:item', newPosition);
            this.cols[columnId][task.dataset.id] = newPosition
          })
      },
      updateKanban: async function(){
        try{
          const response = await fetch('{{ route('update.kanban') }}',{
            method:'PUT',
            headers:{
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body:JSON.stringify({...this.cols})
          })

          if(!response.ok){
            const error = await response.json();
            throw new Error('Something went wrong: '+response.status);
          }

          console.log(response);
        }catch(error){
          console.log(error);
        }finally {
          this.cols = [];
        }
      },
      sorted: async function(event){
        {{-- console.log(event); --}}
        const fromTasks = event.from.querySelectorAll('article');
        const toTasks = event.to.querySelectorAll('article');
        const noTaskElement = event.to.querySelector('#no-task');
        const columnIdFrom = event.from.dataset.col;
        const columnIdTo = event.to.dataset.col;

        if(fromTasks.length <= 0){
          event.from.innerHTML = `<article class='block text-center' id='no-task'>Nenhuma tarefa</article>`
        }

        if(noTaskElement){
          noTaskElement.remove();
        }

        this.createDataToUpdateKanban(fromTasks,columnIdFrom);

        if(columnIdFrom !== columnIdTo){
          this.createDataToUpdateKanban(toTasks,columnIdTo);
        }

        console.log(this.cols);

        await this.updateKanban();
      }
    }">
      <!-- Coluna Peparando -->
      <section class="bg-white rounded-2xl shadow-lg flex flex-col h-[70vh]" >
        <header class="px-4 py-3 border-b border-gray-200">
          <h2 class="text-lg font-semibold bg-red-400 rounded text-white text-center">🔴Preparando</h2>
        </header>
        <div class="flex-1 overflow-y-auto p-4 space-y-4" data-col="1" x-sort x-sort:group="kanban" @end="sorted">
          @forelse ($tasks[1] ?? [] as $task)
            <article class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer" x-sort:item="{{ $task->position_id }}" data-id="{{ $task->id }}">
            <h3 class="font-medium text-gray-800">{{ $task->title }}</h3>
            <p class="text-sm text-gray-500">
              {{ $task->description }}
            </p>
          </article>
          @empty
              <article class="block text-center" id='no-task'>Nenhuma tarefa</article>
          @endforelse
        </div>
      </section>

      <!-- Coluna Desenvolvendo -->
      <section class="bg-white rounded-2xl shadow-lg flex flex-col h-[70vh]">
        <header class="px-4 py-3 border-b border-gray-200">
          <h2 class="text-lg font-semibold bg-orange-400 rounded text-white text-center">🟠Desenvolvendo</h2>
        </header>
          <div class="flex-1 overflow-y-auto p-4 space-y-4" data-col="2" x-sort x-sort:group="kanban" @end="sorted">
           @forelse ($tasks[2] ?? []  as $task)
            <article class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer" x-sort:item="{{ $task->position_id }}" data-id="{{ $task->id }}">
            <h3 class="font-medium text-gray-800">{{ $task->title }}</h3>
            <p class="text-sm text-gray-500">
              {{ $task->description }}
            </p>
          </article>
          @empty
              <article class="block text-center" id='no-task'>Nenhuma tarefa</article>
          @endforelse
        </div>
      </section>

      <!-- Coluna Pronto -->
      <section class="bg-white rounded-2xl shadow-lg flex flex-col h-[70vh]">
        <header class="px-4 py-3 border-b border-gray-200">
          <h2 class="text-lg font-semibold bg-green-400 rounded text-white text-center">🟢Pronto</h2>
        </header>
        <div class="flex-1 overflow-y-auto p-4 space-y-4" data-col="3" x-sort x-sort:group="kanban" @end="sorted">
          @forelse ($tasks[3] ?? [] as $task)
            <article class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 cursor-pointer" x-sort:item="{{ $task->position_id }}" data-id="{{ $task->id }}">
            <h3 class="font-medium text-gray-800">{{ $task->title }}</h3>
            <p class="text-sm text-gray-500">
              {{ $task->description }}
            </p>
          </article>
          @empty
              <article class="block text-center" id='no-task'>Nenhuma tarefa</article>
          @endforelse
        </div>
      </section>
    </main>
  </div>
</body>
</html>
