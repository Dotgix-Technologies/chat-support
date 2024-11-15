
<div class="container">
    <div class="navigation">
        <ul>
            <li>
                <a href="{{url('/')}}">
                    <span class="icon"><i class="fa-solid fa-house" style="color: #3498db;"></i></span>
                    <span class="title">Home</span>
                </a>
            </li>
            <li>
                <a href="{{url('/profile')}}">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <span class="title">Profile</span>
                </a>
            </li>
            <li>
                <a href="{{url('Admin/index')}}">
                    <span class="icon"><i class="fa-solid fa-message"></i></span>
                    <span class="title">Messages</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fa-solid fa-circle-info"></i></span>
                    <span class="title">Help</span>
                </a>
            </li>
            <li>
                <a href="{{url('/profile')}}">
                    <span class="icon"><i class="fa-solid fa-gear"></i></span>
                    <span class="title">Settings</span>
                </a>
            </li>
            <li>
                <a href="{{url('/profile')}}">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <span class="title">Password</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="display: flex; align-items: center;">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="title">SignOut</span>
                    </a>
                </form>
            </li>
            
        </ul>
    </div>

    <div class="toggle" onclick="toggleMenu()"></div>
    <script type="text/javascript">
        function toggleMenu() {
            let navigation = document.querySelector('.navigation');
            let toggle = document.querySelector('.toggle');
            navigation.classList.toggle('active');
            toggle.classList.toggle('active');
        }
    </script>

    <div class="chat">
        <div class="chat-list sm-display-none md-display-none">
            <livewire:consultant.chat-list :selectedConversation="$selectedConversation" :query="$query" />
        </div>
        <div class="chat-box">
            <livewire:consultant.chat-box :selectedConversation="$selectedConversation"/>
        </div>
    </div>
</div>
