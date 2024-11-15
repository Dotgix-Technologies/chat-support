<div x-data="{ height: 0, conversationElement: null }" x-init="conversationElement = document.getElementById('chatbox-body');
height = conversationElement.scrollHeight;
conversationElement.scrollTop = conversationElement.scrollHeight;"
    @messageSent.window="conversationElement.scrollTop = conversationElement.scrollHeight;
                         document.getElementById('message-input').value = '';">


    <div class="chat">
        <div class="chat-history" id="chatbox-body">

            @if ($loadedMessages)
                <div class="message received">
                    <div class="avatar">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <circle cx="12" cy="9" r="3" stroke="#03A84E" stroke-width="1.5">
                                </circle>
                                <circle cx="12" cy="12" r="10" stroke="#03A84E" stroke-width="1.5">
                                </circle>
                                <path
                                    d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                    stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="message-content1">
                        <p>Hello! 👋 We're here to help. How can we assist you today?</p>
                        <span>{{ $conversation->created_at->format('g:i a') }}</span>
                    </div>
                </div>
                @foreach ($loadedMessages as $message)
                    @if ($message->receiver_id == null)
                        <!-- user message send -->
                        <div class="message received">
                            <div class="avatar">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <circle cx="12" cy="9" r="3" stroke="#03A84E" stroke-width="1.5">
                                        </circle>
                                        <circle cx="12" cy="12" r="10" stroke="#03A84E"
                                            stroke-width="1.5"></circle>
                                        <path
                                            d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                            stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="message-content1">

                                @if ($message->type === 'file')
                                    <a href="{{ asset('storage/' . $message->file_path) }}"
                                        download="{{ basename($message->body) }}"title="Download {{ $message->body }}">
                                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z"
                                                stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#03A84E"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                        <span>{{ $message->body }}</span>
                                    </a>
                                @else
                                    <small>{!! nl2br(e($message->body)) !!}</small>
                                @endif
                                <span>{{ $message->created_at->format('g:i a') }}</span>
                            </div>
                        </div>
                        <!-- user message send end -->
                    @else
                        <!-- admin message send -->
                        <div class="message sent">
                            <div class="message-content">
                                @if ($message->type === 'file')
                                    <a href="{{ asset('storage/' . $message->file_path) }}"
                                        download="{{ basename($message->body) }}"title="Download {{ $message->body }}">
                                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z"
                                                stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#03A84E"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                        <span>{{ $message->body }}</span>
                                    </a>
                                @else
                                    <small>{!! nl2br(e($message->body)) !!}</small>
                                @endif
                                <span>{{ $message->created_at->format('g:i a') }} </span>
                            </div>
                        </div>
                        <!-- admin message send end -->
                    @endif
                @endforeach
            @else
                <div class="message received">
                    <div class="avatar">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <circle cx="12" cy="9" r="3" stroke="#03A84E" stroke-width="1.5">
                                </circle>
                                <circle cx="12" cy="12" r="10" stroke="#03A84E" stroke-width="1.5">
                                </circle>
                                <path
                                    d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                    stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="message-content1">
                        <p>Hello! 👋 We're here to help. How can we assist you today?</p>
                        <span>{{ now()->format('g:i a') }}</span>
                    </div>
                </div>
            @endif

            <hr>
        </div> <!-- end chat-history -->
        <form x-data="{
            messageBody: @entangle('messageBody').defer,
            file: @entangle('file').defer,
            fileName: @entangle('fileName').defer,
            uploadProgress: 0,
            uploading: false,
        }" @submit.prevent="$wire.sendMessage" method="POST"
            enctype="multipart/form-data" id="message-form" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="uploadProgress = $event.detail.progress">
            <template x-if="fileName">
                <div class="file-display"
                    style="position: relative; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #f9f9f9;">
                    <!-- Close button positioned in the top right corner -->
                    <button type="button"
                        style="position: absolute; top: 5px; right: 5px; background-color: transparent; border: none; color: #e74c3c; cursor: pointer;"
                        @click="fileName = ''; $refs['file-upload'].value = ''; messageBody = ''"
                        wire:click="removeFile">
                        {{ $uploadProgress }}
                        <svg width="22" height="22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>

                    <!-- SVG Icon -->
                    <div style="text-align: center; margin-bottom: 8px;">
                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-4-4z" stroke="#03A84E"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M14 2v6h6M10 12l2 2 2-2M10 16l2 2 2-2" stroke="#03A84E" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                            </path>
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
                <div class="progress-bar-fill" style="width: 100%; background-color: #03A84E; height: 8px;"
                    :style="{ width: uploadProgress + '%' }">
                </div>
            </div>
            <fieldset>

                <textarea wire:model.defer="messageBody" name="messageBody" id="message-input" placeholder="Type Your Message"
                    style="resize: none;" x-data="{ message: $wire.entangle('messageBody') }" x-model="message"
                    @keydown.enter.prevent="
        if ($event.shiftKey) {
            const cursorPosition = $event.target.selectionStart;
            // Insert a single newline
            message = message.slice(0, cursorPosition) + '\n' + message.slice(cursorPosition);
            
            console.log('Textarea Value After:', message);
        } else {
            console.log('Sending message');
            $wire.sendMessage();
        }
    "
                    autofocus></textarea>





                <input type="file" wire:model="file" style="display: none;"
                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" id="file-upload"
                    x-ref="fileUpload" />
                <label for="file-upload" class="custom-file-upload">
                    <svg class="input-icon-2" width="24" height="24" viewBox="0 0 200 200"
                        xmlns="http://www.w3.org/2000/svg" fill="#494c4e" stroke="#494c4e">
                        <path
                            d="M176.15,99.94a9.67,9.67,0,0,0-14,0l-51.5,51a41,41,0,0,1-58-58l48.5-48.5c10.5-10.5,28-10.5,39,0,8.5,8.5,8.5,22.5,0,31.5l-52.5,52c-3.5,3.5-9,3.5-12,0-3.5-3.5-3.5-9,0-12l51-51a9.9,9.9,0,0,0-14-14l-51,51c-11,11-11,29,0,40.5,11,11,29,11,40.5,0l52.5-52.5a42,42,0,0,0,0-59.5,47.38,47.38,0,0,0-67,0l-48.5,48.5a61.16,61.16,0,0,0,86.5,86.5l51-51A12,12,0,0,0,176.15,99.94Z">
                        </path>
                    </svg>
                </label>
                <button class="send-mess" type="submit" @click="fileName = ''; $refs['file-upload'].value = ''">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M18.61 2.64548C20.1948 2.19021 21.6568 3.65224 21.2016 5.23705L17.1785 19.2417C16.5079 21.5761 13.3904 22.0197 12.1096 19.9629L10.3338 17.1113C9.84262 16.3226 9.96155 15.2974 10.6207 14.6383L14.4111 10.8479C14.8022 10.4567 14.8033 9.82357 14.4134 9.43373C14.0236 9.04389 13.3905 9.04497 12.9993 9.43614L9.20901 13.2265C8.54987 13.8856 7.52471 14.0046 6.73596 13.5134L3.88412 11.7375C1.82737 10.4567 2.27092 7.33918 4.60532 6.66858L18.61 2.64548Z"
                            fill="#03A84E"></path>
                    </svg>
                </button>
            </fieldset>
        </form>




    </div> <!-- end chat -->
    {{-- <script>
        document.getElementById('message-input').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.getElementById('message-form').requestSubmit();
            }
        });
    </script> --}}
</div>
