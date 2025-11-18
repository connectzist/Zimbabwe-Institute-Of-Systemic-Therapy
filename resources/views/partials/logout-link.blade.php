<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<a href="#" class="nav_link"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class='bx bx-log-out nav_icon'></i>
    <span class="nav_name">LogOut</span>
</a>
