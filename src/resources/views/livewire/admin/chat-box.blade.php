        <div x-data="{ height: 0, conversationElement: null }" x-init="conversationElement = document.getElementById('chatbox-body');
        height = conversationElement.scrollHeight;
        conversationElement.scrollTop = conversationElement.scrollHeight;"
            @scroll-bottom.window="conversationElement.scrollTop = conversationElement.scrollHeight"
            @messageSent.window="conversationElement.scrollTop = conversationElement.scrollHeight"
            class="chatbox sm-display-none md-display-none">
            <div class="chatbox-header">
                <div class="chatbox-header-info">
                    <div class="chatbox-back">
                        <a href="{{ url('Admin/index') }}"><svg fill="#3498db" width="25" height="25"
                                viewBox="0 0 200 200" data-name="Layer 1" id="Layer_1"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title></title>
                                    <path
                                        d="M160,89.75H56l53-53a9.67,9.67,0,0,0,0-14,9.67,9.67,0,0,0-14,0l-56,56a30.18,30.18,0,0,0-8.5,18.5c0,1-.5,1.5-.5,2.5a6.34,6.34,0,0,0,.5,3,31.47,31.47,0,0,0,8.5,18.5l56,56a9.9,9.9,0,0,0,14-14l-52.5-53.5H160a10,10,0,0,0,0-20Z">
                                    </path>
                                </g>
                            </svg>
                        </a>
                    </div>
                    <div class="chatbox-avatar"><svg width="50px" height="50px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <circle cx="12" cy="9" r="3" stroke="#3498db" stroke-width="1.5">
                                </circle>
                                <circle cx="12" cy="12" r="10" stroke="#3498db" stroke-width="1.5">
                                </circle>
                                <path
                                    d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                    stroke="#3498db" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg></div>
                    <h3 id="chatbox-title">{{ $selectedConversation->sender_session_id }}</h3>
                </div>


                <div class="chatbox-header-icons">
                    <label for="dropdown-toggle">
                        <i class="fas fa-ellipsis-v"></i>
                    </label>
                    <input type="checkbox" id="dropdown-toggle" />
                    <div class="dropdown-content">
                        <!-- remove this condition if you want to reassign chats --->
                        @if ($Assignedto->role == 'Admin')
                            <a href="#"
                                @click="$wire.set('conversationId', {{ $selectedConversation->id }}); openAssignModal()">Assign
                                Now</a>
                        @endif
                        @if ($selectedConversation->status === 'archived')
                            <a href="#"
                                wire:click.prevent="unarchiveChat({{ $selectedConversation->id }})">Unarchive
                                Chat</a>
                        @else
                            <a href="#" wire:click.prevent="archiveChat({{ $selectedConversation->id }})">Archive
                                Chat</a>
                        @endif
                    </div>
                </div>


            </div>
            <div class="chatbox-body" id="chatbox-body">
                <div class="chatbox">
                    @if ($loadedMessages)
                        @foreach ($loadedMessages as $message)
                            @if ($message->receiver_id != null)
                                <!-- For received text messages -->
                                <div class="message received">
                                    <div class="avatar">
                                        <!-- SVG for avatar -->
                                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                                            <circle cx="12" cy="9" r="3" stroke="#3498db"
                                                stroke-width="1.5"></circle>
                                            <circle cx="12" cy="12" r="10" stroke="#3498db"
                                                stroke-width="1.5"></circle>
                                            <path
                                                d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                                stroke="#3498db" stroke-width="1.5" stroke-linecap="round"></path>
                                        </svg>
                                    </div>
                                    <div class="message-content1" >
                                        @if ($message->type === 'file')
                                            <a href="{{ asset('storage/' . $message->file_path) }}"
                                                download="{{ basename($message->body) }}"title="Download {{ $message->body }}">
                                                <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z"
                                                        stroke="#3498db" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#3498db"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                                <span>{{ $message->body }}</span>
                                            </a>
                                        @else
                                            <small style="max-width: 30ch;">{!! nl2br(e($message->body)) !!}</small>
                                        @endif
                                        <span>{{ $message->created_at->format('g:i a') }}</span>
                                    </div>
                                </div>
                            @else
                                <!-- For sent text messages -->
                                <div class="message sent">
                                    <div class="message-content">
                                        @if ($message->type === 'file')
                                            <a href="{{ asset('storage/' . $message->file_path) }}"
                                                download="{{ basename($message->body) }}"
                                                title="Download {{ $message->body }}">
                                                <svg width="40px" height="40px" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    stroke="#3498db">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z"
                                                        stroke="#3498db" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#3498db"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                </svg>
                                                <span>{{ $message->body }}</span>
                                            </a>
                                        @else
                                            <small style="max-width: 30ch;">{!! nl2br(e($message->body)) !!}</small>
                                        @endif
                                        <span>{{ $message->created_at->format('g:i a') }}</span>
                                    </div>
                                    <div class="avatar">

                                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <circle cx="12" cy="9" r="3" stroke="#3498db"
                                                    stroke-width="1.5"></circle>
                                                <circle cx="12" cy="12" r="10" stroke="#3498db"
                                                    stroke-width="1.5"></circle>
                                                <path
                                                    d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                                    stroke="#3498db" stroke-width="1.5" stroke-linecap="round">
                                                </path>
                                            </g>
                                        </svg>
                                        @if ($message->sender_id === auth()->id())
                                            <span style="color: grey; font-size: 0.8em;">You</span>
                                        @else
                                            <span style="color: grey; font-size: 0.8em;display:flex">Admin:
                                                {{ $message->sender->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>


            <form x-data="{
                messageBody: '',
                file: '',
                fileName: '',
                uploadProgress: 0,
                uploading: false,
                init() {
                    this.messageBody = @entangle('messageBody').defer;
                    this.file = @entangle('file').defer;
                }
            }" wire:submit.prevent="sendMessage" method="POST"
                enctype="multipart/form-data" id="message-form" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="uploadProgress = $event.detail.progress">

                <!-- Display file name and cancel button if a file is selected -->
                <template x-if="fileName">
                    <div class="file-display"
                        style="position: relative; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #f9f9f9;">
                        <!-- Close button positioned in the top right corner -->
                        <button type="button"
                            style="position: absolute; top: 5px; right: 5px; background-color: transparent; border: none; color: #e74c3c; cursor: pointer;"
                            @click="fileName = ''; $refs['file-upload'].value = ''; messageBody = ''"
                            wire:click="removeFile">
                            <svg width="22" height="22" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </button>

                        <!-- SVG Icon -->
                        <div style="text-align: center; margin-bottom: 8px;">
                            <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" stroke="#3498db">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z" stroke="#3498db"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#3498db" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>

                        <!-- File Name -->
                        <div style="text-align: center; font-size: 14px; color: #333;">
                            <span x-text="fileName"></span>
                        </div>
                    </div>
                </template>

                <!-- File Upload Progress Bar -->
                <div x-show="uploading" class="progress-bar"
                    style="width: 100%; background-color: #f3f3f3; border-radius: 8px; overflow: hidden; margin-top: 10px;">
                    <div class="progress-bar-fill" style="width: 100%; background-color: #3498db; height: 8px;"
                        :style="{ width: uploadProgress + '%' }">
                    </div>
                </div>
                <div class="chatbox-footer">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <textarea wire:model.defer="messageBody" name="messageBody" id="message-input" placeholder="Type Your Message"
                                style="resize: none;" :disabled="fileName !== ''" autofocus></textarea>
                        </div>
                        <div class="icon-container">
                            <div class="icon-wrapper">
                                <label for="file-upload" class="custom-file-upload">
                                    <svg class="input-icon-2" width="42px" height="42px" viewBox="0 0 200 200"
                                        xmlns="http://www.w3.org/2000/svg" fill="#494c4e" stroke="#494c4e">
                                        <path
                                            d="M176.15,99.94a9.67,9.67,0,0,0-14,0l-51.5,51a41,41,0,0,1-58-58l48.5-48.5c10.5-10.5,28-10.5,39,0,8.5,8.5,8.5,22.5,0,31.5l-52.5,52c-3.5,3.5-9,3.5-12,0-3.5-3.5-3.5-9,0-12l51-51a9.9,9.9,0,0,0-14-14l-51,51c-11,11-11,29,0,40.5,11,11,29,11,40.5,0l52.5-52.5a42,42,0,0,0,0-59.5,47.38,47.38,0,0,0-67,0l-48.5,48.5a61.16,61.16,0,0,0,86.5,86.5l51-51A12,12,0,0,0,176.15,99.94Z">
                                        </path>
                                    </svg>
                                </label>
                                <input type="file" wire:model="file" style="display: none;"
                                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                    id="file-upload" x-ref="fileUpload" />
                            </div>
                        </div>
                    </div>
                    <div class="button-container">
                        <button type="submit"
                            @click="fileName = ''; $refs['file-upload'].value = ''; messageBody = '';">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
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

            <script>
                function openAssignModal() {
                    window.dispatchEvent(new CustomEvent('open-assign-modal'));
                }

                function closeAssignModal() {
                    window.dispatchEvent(new CustomEvent('close-assign-modal'));
                }
            </script>
            <script>
                document.getElementById('message-input').addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        if (event.shiftKey) {
                            // If Shift + Enter is pressed, insert a line break
                            event.preventDefault();
                            const cursorPosition = this.selectionStart;
                            const textBeforeCursor = this.value.substring(0, cursorPosition);
                            const textAfterCursor = this.value.substring(cursorPosition);
                            this.value = textBeforeCursor + "\n" + textAfterCursor;
                            this.selectionStart = this.selectionEnd = cursorPosition + 1;
                        } else {
                            // If only Enter is pressed, submit the form
                            event.preventDefault();
                            document.getElementById('message-form').requestSubmit();
                        }
                    }
                });

                window.addEventListener('chat-update', function() {
                    let conversationElement = document.getElementById('chatbox-body');
                    if (conversationElement) {
                        console.log('Scrolling chatbox-body');
                        requestAnimationFrame(() => {
                            conversationElement.scrollTop = conversationElement.scrollHeight;
                        });
                    }
                });

                window.addEventListener('messageSent', function() {
                    let conversationElement = document.getElementById('chatbox-body');
                    if (conversationElement) {
                        console.log('Scrolling chatbox-body');
                        requestAnimationFrame(() => {
                            conversationElement.scrollTop = conversationElement.scrollHeight;
                        });
                    }

                    // Clear the textarea
                    let messageInput = document.getElementById('message-input');
                    if (messageInput) {
                        messageInput.value = '';
                    }
                });
            </script>


        </div>
