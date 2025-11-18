<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">User Signature Management</h1>
                <p class="text-sm text-gray-500">Capture and manage user signatures for document authorization</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Roles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Signature</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user['email'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user['roles'] ?: 'No roles' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user['has_signature'])
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-600 font-medium">✓ Captured</span>
                                            <img src="{{ asset('storage/' . $user['signature_data']) }}"
                                                 alt="Signature"
                                                 class="h-8 border border-gray-300 rounded">
                                        </div>
                                    @else
                                        <span class="text-red-600">✗ Not captured</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openSignatureModal({{ $user['id'] }})"
                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium">
                                            {{ $user['has_signature'] ? 'Update' : 'Capture' }}
                                        </button>
                                        @if($user['has_signature'])
                                            <button wire:click="deleteSignature({{ $user['id'] }})"
                                                wire:confirm="Are you sure you want to delete this signature?"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Signature Modal -->
    @if($showSignatureModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6" x-data="signaturePad()" x-init="init()">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Capture Signature</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Current Signature Preview -->
                        @if($currentUserSignature)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Signature:</label>
                                <img src="{{ asset('storage/' . $currentUserSignature) }}"
                                     alt="Current Signature"
                                     class="border border-gray-300 rounded p-2 bg-white max-h-32">
                            </div>
                        @endif

                        <!-- Signature Pad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Draw your signature:</label>
                            <div class="border-2 border-gray-300 rounded-lg overflow-hidden">
                                <canvas x-ref="canvas"
                                        width="600"
                                        height="200"
                                        class="w-full cursor-crosshair bg-white"
                                        style="touch-action: none; display: block;"
                                        x-on:mousedown="startDrawing($event)"
                                        x-on:mousemove="draw($event)"
                                        x-on:mouseup="stopDrawing()"
                                        x-on:mouseleave="stopDrawing()"
                                        x-on:touchstart.prevent="startDrawing($event)"
                                        x-on:touchmove.prevent="draw($event)"
                                        x-on:touchend.prevent="stopDrawing()">
                                </canvas>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <button x-on:click="clearCanvas()"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Clear
                            </button>
                            <div class="flex gap-2">
                                <button wire:click="closeModal"
                                        type="button"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                    Cancel
                                </button>
                                <button x-on:click="saveSignature()"
                                        type="button"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                    Save Signature
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function signaturePad() {
        return {
            canvas: null,
            ctx: null,
            isDrawing: false,
            lastX: 0,
            lastY: 0,

            init() {
                this.canvas = this.$refs.canvas;
                if (!this.canvas) {
                    console.error('Canvas not found');
                    return;
                }

                this.ctx = this.canvas.getContext('2d');

                // Set drawing styles
                this.ctx.strokeStyle = '#000000';
                this.ctx.lineWidth = 2;
                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';

                console.log('Signature pad initialized');
            },

            getCoordinates(e) {
                const rect = this.canvas.getBoundingClientRect();
                const scaleX = this.canvas.width / rect.width;
                const scaleY = this.canvas.height / rect.height;

                let clientX, clientY;

                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                } else {
                    clientX = e.clientX;
                    clientY = e.clientY;
                }

                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            },

            startDrawing(e) {
                e.preventDefault();
                this.isDrawing = true;
                const coords = this.getCoordinates(e);
                this.lastX = coords.x;
                this.lastY = coords.y;

                // Draw a dot for single click
                this.ctx.beginPath();
                this.ctx.arc(coords.x, coords.y, this.ctx.lineWidth / 2, 0, Math.PI * 2);
                this.ctx.fill();
            },

            draw(e) {
                if (!this.isDrawing) return;
                e.preventDefault();

                const coords = this.getCoordinates(e);

                this.ctx.beginPath();
                this.ctx.moveTo(this.lastX, this.lastY);
                this.ctx.lineTo(coords.x, coords.y);
                this.ctx.stroke();

                this.lastX = coords.x;
                this.lastY = coords.y;
            },

            stopDrawing() {
                this.isDrawing = false;
            },

            clearCanvas() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            },

            saveSignature() {
                const dataURL = this.canvas.toDataURL('image/png');
                @this.set('signatureData', dataURL);
                @this.call('saveSignature');
            }
        }
    }
</script>
