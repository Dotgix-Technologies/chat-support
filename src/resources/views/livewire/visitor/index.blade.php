<div x-data="{ open: false }">
    <link rel="stylesheet" href="{{ asset('visitor/index.css') }}">
    <!-- Toggleable Box -->
    <div x-show="open" class="chat-box">

        <header class="clearfix">
            <!-- Toggle chat open/close -->
            <button @click="open = !open" class="chat-close">x</button>

            <h4 title="{{ session()->getId() }}">{{ substr(session()->getId(), 0, 25) . '...' }}</h4>
            <span class="chat-message-counter" x-show="open">3</span>
        </header>
        @if ($showMainChat == true)
            @livewire('visitor.chat')
        @else
            <div class="chat">
                <div class="chatbox-index-container">
                    <div class="chatbox-index-header">
                        <h1 style="font-size: 30px;"><strong>Welcome to tawk.to</strong></h1>
                        <p style="font-size: small;">Search our Knowledge Base or start a chat. We're here to help
                            you 24 x 7</p>
                    </div>

                    <div class="chatbox-index-aass">
                        <div class="chatbox-index-conversations">
                            <div class="convo-header">
                                <h2>Conversations</h2>
                            </div>
                            @if ($conversation)
                                @if ($conversation->visitorunreadMessagesCount() > 0)
                                    <button class="count-button"> {{ $conversation->visitorunreadMessagesCount() }}</button>
                                @endif
                                <button style="width: 100%" wire:click="showChatBox">
                                    <div class="convo-item">
                                        <div class="convo-icon">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" stroke="#03A84E">
                                                <circle cx="12" cy="9" r="3" stroke="#03A84E"
                                                    stroke-width="1.5"></circle>
                                                <circle cx="12" cy="12" r="10" stroke="#03A84E"
                                                    stroke-width="1.5"></circle>
                                                <path
                                                    d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                                                    stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"></path>
                                            </svg>
                                        </div>
                                        <div class="convo-text">
                                            <p><strong>{{ $conversationStatus }}</strong></p>
                                            @if ($conversation->visitorunreadMessagesCount() > 0)
                                            <b>{{ $lastMessage ? substr($lastMessage->body, 0, 100) . (strlen($lastMessage->body) > 100 ? '...' : '') : 'No messages yet' }}
                                            </b>
                                            @else
                                            <p>{{ $lastMessage ? substr($lastMessage->body, 0, 100) . (strlen($lastMessage->body) > 100 ? '...' : '') : 'No messages yet' }}
                                            </p>
                                            @endif


                                        </div>
                                        <div class="convo-actions">
                                            <div class="convo-status">{{ $conversationStatus }}</div>

                                            <svg width="32" height="32" viewBox="0 0 32 32" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#00a336">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <title>arrow-right-circle</title>
                                                    <desc>Created with Sketch Beta.</desc>
                                                    <defs> </defs>
                                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd" sketch:type="MSPage">
                                                        <g id="Icon-Set-Filled" sketch:type="MSLayerGroup"
                                                            transform="translate(-310.000000, -1089.000000)"
                                                            fill="#00a336">
                                                            <path
                                                                d="M332.535,1105.88 L326.879,1111.54 C326.488,1111.93 325.855,1111.93 325.465,1111.54 C325.074,1111.15 325.074,1110.51 325.465,1110.12 L329.586,1106 L319,1106 C318.447,1106 318,1105.55 318,1105 C318,1104.45 318.447,1104 319,1104 L329.586,1104 L325.465,1099.88 C325.074,1099.49 325.074,1098.86 325.465,1098.46 C325.855,1098.07 326.488,1098.07 326.879,1098.46 L332.535,1104.12 C332.775,1104.36 332.85,1104.69 332.795,1105 C332.85,1105.31 332.775,1105.64 332.535,1105.88 L332.535,1105.88 Z M326,1089 C317.163,1089 310,1096.16 310,1105 C310,1113.84 317.163,1121 326,1121 C334.837,1121 342,1113.84 342,1105 C342,1096.16 334.837,1089 326,1089 L326,1089 Z"
                                                                id="arrow-right-circle" sketch:type="MSShapeGroup">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>


                                        </div>
                                    </div>
                                </button>
                            @endif
                            <div class="search-container">
                                <input type="text" placeholder="Search for answers">
                                <button class="search-btn"><svg width="22" height="22" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M14.9536 14.9458L21 21M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </g>
                                    </svg></button>
                            </div>

                            <button class="new-convo-btn" wire:click="showChatBox">
                                <svg width="32" height="32" viewBox="0 0 1024 1024" class="icon" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M633.319 722.634L429 860.298V672.034z" fill="#fffffffffff"></path>
                                        <path
                                            d="M446.662 681.407l388.442 104.557L960 163.702l-159.706 99.826L64 723.667z"
                                            fill="#ffffff"></path>
                                        <path d="M446.662 681.407L960 163.702l-159.706 99.826L64 723.667z"
                                            fill="#3ee676"></path>
                                    </g>
                                </svg>New Conversation</button>
                        </div>
                    </div>

                    <div class="chatbox-index-footer">
                        <a href="#" class="add-chat-btn">Add free live chat to your site</a>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        @endif
    </div>
    <!-- Toggle Button -->
    <button @click="open = !open" class="toggle-button">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            transform="matrix(-1, 0, 0, 1, 0, 0)rotate(0)">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path
                    d="M4.99951 16.55V19.9C4.99922 20.3102 5.11905 20.7114 5.34418 21.0542C5.56931 21.397 5.88994 21.6665 6.26642 21.8292C6.6429 21.9919 7.05875 22.0408 7.46271 21.9698C7.86666 21.8989 8.24103 21.7113 8.53955 21.4301L11.1495 18.9701H12.0195C17.5395 18.9701 22.0195 15.1701 22.0195 10.4701C22.0195 5.77009 17.5395 1.97009 12.0195 1.97009C6.49953 1.97009 2.01953 5.78009 2.01953 10.4701C2.042 11.6389 2.32261 12.7882 2.84125 13.8358C3.35989 14.8835 4.10373 15.8035 5.01953 16.53L4.99951 16.55Z"
                    stroke="#03A84E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M17 9.5H7" stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"></path>
                <path d="M13 12.5H7" stroke="#03A84E" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </g>
        </svg>
    </button>
    <script>
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
