
<div class="d-flex gap-2 grid-img">
    <div class="card align-self-center {{($view=='two')?'active':''}}"> 
        <a href="javascript:void(0)" wire:click="dataView('two')">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
            <rect width="30" height="30" fill="white"/>
            <path d="M14 28.875C14 28.944 13.944 29 13.875 29H1.125C1.05596 29 1 28.944 1 28.875V1.125C1 1.05596 1.05596 1 1.125 1H13.875C13.944 1 14 1.05596 14 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M29 28.875C29 28.944 28.944 29 28.875 29H16.125C16.056 29 16 28.944 16 28.875V1.125C16 1.05596 16.056 1 16.125 1H28.875C28.944 1 29 1.05596 29 1.125V28.875Z" fill="#D6D6D6"/>
            </svg>
        </a>
    </div>
    <div class="card align-self-center sys-view {{($view=='three')?'active':''}}"> 
        <a href="javascript:void(0)" wire:click="dataView('three')">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
            <rect width="30" height="30" fill="white"/>
            <path d="M9.75 28.875C9.75 28.944 9.69404 29 9.625 29H1.125C1.05596 29 1 28.944 1 28.875V1.125C1 1.05596 1.05596 1 1.125 1H9.625C9.69404 1 9.75 1.05596 9.75 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M19.375 28.875C19.375 28.944 19.319 29 19.25 29H10.75C10.681 29 10.625 28.944 10.625 28.875V1.125C10.625 1.05596 10.681 1 10.75 1H19.25C19.319 1 19.375 1.05596 19.375 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M29 28.875C29 28.944 28.944 29 28.875 29H20.375C20.306 29 20.25 28.944 20.25 28.875V1.125C20.25 1.05596 20.306 1 20.375 1H28.875C28.944 1 29 1.05596 29 1.125V28.875Z" fill="#D6D6D6"/>
            </svg>        
        </a>
    </div>
    <div class="card align-self-center sys-views {{($view=='four')?'active':''}}"> 
        <a href="javascript:void(0)" wire:click="dataView('four')">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
            <rect width="30" height="30" fill="white"/>
            <path d="M7.25 28.875C7.25 28.944 7.19404 29 7.125 29H1.125C1.05596 29 1 28.944 1 28.875V1.125C1 1.05596 1.05596 1 1.125 1H7.125C7.19404 1 7.25 1.05596 7.25 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M14.5 28.875C14.5 28.944 14.444 29 14.375 29H8.375C8.30596 29 8.25 28.944 8.25 28.875V1.125C8.25 1.05596 8.30596 1 8.375 1H14.375C14.444 1 14.5 1.05596 14.5 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M21.75 28.875C21.75 28.944 21.694 29 21.625 29H15.625C15.556 29 15.5 28.944 15.5 28.875V1.125C15.5 1.05596 15.556 1 15.625 1H21.625C21.694 1 21.75 1.05596 21.75 1.125V28.875Z" fill="#D6D6D6"/>
            <path d="M29 28.875C29 28.944 28.944 29 28.875 29H22.875C22.806 29 22.75 28.944 22.75 28.875V1.125C22.75 1.05596 22.806 1 22.875 1H28.875C28.944 1 29 1.05596 29 1.125V28.875Z" fill="#D6D6D6"/>
        </svg>
        </a>
    </div>
    <div class="card align-self-center {{($view=='one')?'active':''}}"> 
        <a href="javascript:void(0)" wire:click="dataView('one')">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
        <path d="M28 27.875C28 27.944 27.944 28 27.875 28H0.125C0.0559639 28 0 27.944 0 27.875V0.125C0 0.0559639 0.0559644 0 0.125 0H27.875C27.944 0 28 0.0559644 28 0.125V27.875Z" fill="#D6D6D6"/>
        </svg>
        </a>
    </div>
</div>