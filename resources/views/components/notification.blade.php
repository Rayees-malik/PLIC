<div
id="notification"
x-data="{
    notifications: [],
    add(e) {
        this.notifications.push({
            id: e.timeStamp,
            type: e.detail.type,
            content: e.detail.content,
        })
    },
    remove(notification) {
        this.notifications = this.notifications.filter(i => i.id !== notification.id)
    },
}"
@notify.window="add($event)"
class="tw-z-10 tw-fixed tw-top-16 tw-right-0 tw-pr-4 tw-pb-4 tw-max-w-xs tw-w-full tw-flex tw-flex-col tw-space-y-4 tw-sm:justify-start"
role="status"
aria-live="polite"
>
    <!-- Notification -->
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-data="{
                show: false,
                init() {
                    this.$nextTick(() => this.show = true)

                    setTimeout(() => this.transitionOut(), 4000)
                },
                transitionOut() {
                    this.show = false

                    setTimeout(() => this.remove(this.notification), 500)
                },
            }"
            x-show="show"
            x-transition.duration.500ms
            class="tw-relative tw-max-w-sm tw-w-full tw-bg-white tw-pl-6 tw-pr-4 tw-py-4 tw-border tw-border-gray-200 tw-rounded-md tw-shadow-lg tw-pointer-events-auto"
        >
            <div class="tw-flex tw-items-start">
                <!-- Icons -->
                <div x-show="notification.type === 'info'" class="tw-flex-shrink-0">
                    <span aria-hidden="true" class="tw-w-6 tw-h-6 tw-inline-flex tw-items-center tw-justify-center tw-text-xl tw-font-bold tw-text-gray-400 tw-border-2 tw-border-gray-400 tw-rounded-full">!</span>
                    <span class="tw-sr-only">Information:</span>
                </div>

                <div x-show="notification.type === 'success'" class="flex-shrink-0">
                    <span aria-hidden="true" class="tw-w-6 tw-h-6 tw-inline-flex tw-items-center tw-justify-center tw-text-lg tw-font-bold tw-text-green-600 tw-border-2 tw-border-green-600 tw-rounded-full">&check;</span>
                    <span class="tw-sr-only">Success:</span>
                </div>

                <div x-show="notification.type === 'error'" class="tw-flex-shrink-0">
                    <span aria-hidden="true" class="tw-w-6 tw-h-6 tw-inline-flex tw-items-center tw-justify-center tw-text-lg tw-font-bold tw-text-red-600 tw-border-2 tw-border-red-600 tw-rounded-full">&times;</span>
                    <span class="tw-sr-only">Error:</span>
                </div>

                <!-- Text -->
                <div class="tw-ml-3 tw-w-0 tw-flex-1">
                    <p x-text="notification.content" class="tw-mb-0 tw-text-sm tw-leading-5 tw-font-medium tw-text-gray-900"></p>
                </div>

                <!-- Remove button -->
                <div class="tw-ml-4 tw-flex-shrink-0 tw-flex">
                    <button @click="transitionOut()" type="button" class="tw-inline-flex tw-text-gray-400">
                        <svg aria-hidden class="tw-h-5 tw-w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="tw-sr-only">Close notification</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
