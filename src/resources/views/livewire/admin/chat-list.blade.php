<div class="sidebar "wire:poll>
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.75);
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }
    </style>
    <div class="sidebar-header">
        <h1>Chats</h1>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
    <div class="messages-list" wire:poll>
        <div class="archive">
            <button wire:click="filterChat('all')">All</button>
            <button wire:click="filterChat('general')">General</button>
            <button wire:click="filterChat('assigned')">Assigned</button>
            <button wire:click="filterChat('unassigned')">Unassigned</button>
            <button wire:click="filterChat('archived')">Archived</button>
        </div>

        <ul>
            @if ($conversations)

                @foreach ($conversations as $conversation)
                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}">
                        {{ now() }}
                        <div class="message-item active">
                            <a href="{{ url('Admin/chat', $conversation->id) }}" class="avatar-link">
                                <div class="avatar">
                                    <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <circle cx="12" cy="9" r="3" stroke="#3498db"
                                                stroke-width="1.5"></circle>
                                            <circle cx="12" cy="12" r="10" stroke="#3498db"
                                                stroke-width="1.5"></circle>
                                            <path
                                                d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                                stroke="#3498db" stroke-width="1.5" stroke-linecap="round"></path>
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
                                            <p title="{{ $conversation->messages?->last()?->body ?? 'New Visitor' }}">
                                                {{ substr($conversation->messages?->last()?->body ?? 'New Visitor', 0, 20) . '...' }}
                                            </p>
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
                                        @if ($conversation->receiver->role == 'Admin')
                                            <a href="#"
                                                @click="$wire.set('selectedConversationId', {{ $conversation->id }}); ClopenAssignModal()">Assign
                                                Now </a>
                                        @endif
                                        @if ($conversation->status === 'archived')
                                            <a href="#"
                                                wire:click.prevent="unarchiveChat({{ $conversation->id }})">Unarchive
                                                Chat</a>
                                        @else
                                            <a href="#"
                                                wire:click.prevent="archiveChat({{ $conversation->id }})">Archive
                                                Chat</a>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </li>
                @endforeach
            @endif
            <!-- Modal for Assigning Consultant -->
            <!-- Modal for Assigning Consultant -->
            <div x-data="{ open: false }" x-show="open" @open-assign-modal.window="open = true"
                @close-assign-modal.window="open = false"
                class="modal fixed z-50 inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;">
                <div class="modal-content bg-white rounded-lg p-6 shadow-lg"
                    style="width: 90%; max-width: 500px; position: relative; background-color: #f9f9f9; border: 1px solid #ddd; padding: 20px;">
                    <!-- Modal Header -->
                    <div class="modal-header flex justify-between items-center"
                        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                        <h5 class="modal-title" style="font-size: 18px; font-weight: bold; color: #333;">Assign
                            Consultant</h5>
                        <button @click="open = false"
                            style="background: none; border: none; cursor: pointer; color: #e53e3e; font-size: 14px;">
                            &#10005;
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body" style="padding-top: 15px;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($consultants as $consultant)
                                <li style="margin-bottom: 10px;">
                                    <a href="#" wire:click.prevent="assignConsultant({{ $consultant->id }})"
                                        @click="open = false"
                                        style="display: block; padding: 10px; background-color: #f1f1f1; border-radius: 5px; text-decoration: none; color: #333; transition: background-color 0.3s;">
                                        {{ $consultant->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </ul>
        <!-- Add more message items as needed -->
    </div>
</div>
<script>
    function ClopenAssignModal() {
        window.dispatchEvent(new CustomEvent('open-assign-modal'));
    }

    function closeAssignModal() {
        window.dispatchEvent(new CustomEvent('close-assign-modal'));
    }
</script>

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
