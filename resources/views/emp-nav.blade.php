<nav class="navbar nav-home navbar-expand-lg navbar-light bg-light mynavbar">
    <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('logo.png')}}" class="kclogo" alt="Logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
            <a href="/emp_home/{{auth()->user()->id}}" class="nav-link">Home</a>
        </li>
        <ul class="navbar-nav">
          <li class="nav-item">
              <a href="/emp_home/{{auth()->user()->id}}/leaves" class="nav-link">Leaves</a>
          </li>
        @role('manager')
        <li class="nav-item">
            <a href="/emp_home/{{auth()->user()->id}}/branchDetails" class="nav-link">Branch Details</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">Borrowed items</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Students
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{route('registered_students',['branch_id'=>auth()->user()->branch_id])}}">Registered Students</a>
            <a class="dropdown-item" href="{{route('enrolled_students',['branch_id'=>auth()->user()->branch_id])}}">Enrolled Students</a>
          </div>
        </li>

        @endrole
        <li class="nav-item">
            <a href="{{ route('logout')}}" class="nav-link">Logout</a>
        </li>

      </ul>
    </div>
  </nav>