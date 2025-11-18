<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">My Profile</h1>
                <p class="text-sm text-gray-500">Manage your account settings and signature</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            @if (session()->has('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
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

            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>
                <form wire:submit.prevent="updateProfile" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Roles</label>
                        <div class="text-sm text-gray-600">
                            {{ $user->roles->pluck('name')->implode(', ') ?: 'No roles assigned' }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>
                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" wire:model="currentPassword"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('currentPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" wire:model="newPassword"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('newPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" wire:model="newPasswordConfirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Signature Management -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Digital Signature</h2>
                <p class="text-sm text-gray-600 mb-4">Your signature will be used for document authorization and approvals.</p>

                @if($currentUserSignature)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Signature:</label>
                        <div class="inline-block border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                            <img src="{{ asset('storage/' . $currentUserSignature) }}"
                                alt="Your Signature"
                                class="max-h-24">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="openSignatureModal"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Update Signature
                        </button>
                        <button wire:click="deleteSignature"
                            wire:confirm="Are you sure you want to delete your signature?"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                            Delete Signature
                        </button>
                    </div>
                @else
                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No signature captured</p>
                        <button wire:click="openSignatureModal"
                            class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            Capture Signature
                        </button>
                    </div>
                @endif
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
                        <h3 class="text-lg font-semibold text-gray-900">Capture Your Signature</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Signature Pad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Draw your signature below:</label>
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
