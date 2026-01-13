<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation"
    data-accordion="false" id="navigation">

    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('customers.index') }}" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Clientes</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('users.index') }}" class="nav-link">
            <i class="nav-icon bi bi-person-gear"></i>
            <p>Usuários</p>
        </a>
    </li>


</ul>
