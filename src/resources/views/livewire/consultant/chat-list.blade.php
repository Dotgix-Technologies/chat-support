<div class="sidebar">
    <div class="sidebar-header">
        <h1>Chats</h1>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
    <div class="messages-list">
        <div class="archive">
            <button wire:click="filterChat('all')">All</button>
            <button wire:click="filterChat('general')">General</button>
            <button wire:click="filterChat('archived')">Archived</button>
        </div>
        <ul>
            @if ($conversations)
                @foreach ($conversations as $conversation)
                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}">
                        {{ now() }}
                        <div class="message-item active" onclick="selectChat('chat1')">
                            <a href="{{ url('Consultant/chat', $conversation->id) }}" class="avatar-link">
                                <div class="avatar"><svg width="40px" height="40px" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#008000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <circle cx="12" cy="9" r="3" stroke="#008000"
                                                stroke-width="1.5"></circle>
                                            <circle cx="12" cy="12" r="10" stroke="#008000"
                                                stroke-width="1.5"></circle>
                                            <path
                                                d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                                stroke="#008000" stroke-width="1.5" stroke-linecap="round"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="message-info">
                                    <div>
                                        <div>
                                            @if ($conversation->unreadMessagesCount() > 0)
                                                <button> {{ $conversation->unreadMessagesCount() }}</button>
                                            @endif
                                        </div>
                                        <div>
                                            <span>{{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}</span>
                                        </div>
                                        <div>
                                            <h4 title="{{ $conversation->sender_session_id }}">
                                                {{ substr($conversation->sender_session_id, 0, 25) . '...' }}</h4>
                                                @if ($conversation->unreadMessagesCount() > 0)
                                            <b style="color: rgb(77, 77, 77)" title="{{ $conversation->messages?->last()?->body ?? 'New Visitor' }}">{{ substr($conversation->messages?->last()?->body ?? 'New Visitor', 0, 20) . '...' }}
                                            </b>
                                            @else
                                            <p title="{{ $conversation->messages?->last()?->body ?? 'New Visitor' }}">{{ substr($conversation->messages?->last()?->body ?? 'New Visitor', 0, 20) . '...' }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div>
                                <div class="dropdown">
                                    <div class="dropbtn">
                                        <a href="#" onclick="toggleDropdown(event)">
                                            <svg style="float: right; margin-top: -30px;" width="20px" height="20px"
                                                viewBox="0 0 24 24" id="three-dots" xmlns="http://www.w3.org/2000/svg">
                                                <g id="_20x20_three-dots--grey" data-name="20x20/three-dots--grey"
                                                    transform="translate(24) rotate(90)">
                                                    <rect id="Rectangle" width="24" height="24" fill="none" />
                                                    <circle id="Oval" cx="1" cy="1" r="1"
                                                        transform="translate(5 11)" stroke="#000000"
                                                        stroke-miterlimit="10" stroke-width="0.5" />
                                                    <circle id="Oval-2" data-name="Oval" cx="1"
                                                        cy="1" r="1" transform="translate(11 11)"
                                                        stroke="#000000" stroke-miterlimit="10" stroke-width="0.5" />
                                                    <circle id="Oval-3" data-name="Oval" cx="1"
                                                        cy="1" r="1" transform="translate(17 11)"
                                                        stroke="#000000" stroke-miterlimit="10" stroke-width="0.5" />
                                                </g>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="dropdown-12content">
                                        @if($conversation->status === 'archived')
                                            <a href="#"
                                               wire:click.prevent="unarchiveChat({{ $conversation->id }})">Unarchive Chat</a>
                                        @else
                                            <a href="#"
                                               wire:click.prevent="archiveChat({{ $conversation->id }})">Archive Chat</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <!-- Add more message items as needed -->
    </div>
</div>
<script>
    function toggleDropdown(event) {
        event.preventDefault(); // Prevent the default anchor behavior

        // Find the closest parent with the class "dropdown" and toggle the "show" class
        var dropdown = event.target.closest('.dropdown');
        dropdown.classList.toggle('show');
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.closest('.dropdown')) {
            var dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('show');
            });
        }
    }


    document.addEventListener('alpine:init', () => {
        Alpine.data('chatlist', () => ({
            selectedChat: null,

            selectChat(chatId) {
                this.selectedChat = chatId;
                Livewire.emit('selectChat', chatId); // Emitting the chat ID to Livewire
            },

            searchChat(query) {
                Livewire.emit('searchChat', query); // Emitting the search query to Livewire
            },

            filterChat(filterType) {
                Livewire.emit('filterChat', filterType); // Emitting the filter type to Livewire
            },
        }));
    });
</script>
