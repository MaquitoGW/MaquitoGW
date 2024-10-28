function _construct(id) {

    // Arrays dos botoes
    const buttons = {
        'undo': `<svg class="svg-inline--fa fa-rotate-left fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="rotate-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M62.07 39.961L97.883 75.795C141.668 37.262 197.147 16 255.989 16C320.086 16 380.37 40.969 425.684 86.312C471.03 131.625 496 191.906 496 256S471.03 380.375 425.684 425.688C380.37 471.031 320.086 496 255.989 496C211.848 496 169.517 484.158 132.637 461.977C106.328 446.153 102.872 409.111 124.581 387.402L124.581 387.402C139.725 372.259 163.415 368.379 181.745 379.453C203.924 392.851 229.404 400 255.989 400C294.46 400 330.618 385.031 357.806 357.812C385.026 330.625 399.996 294.469 399.996 256S385.026 181.375 357.806 154.188C330.618 126.969 294.46 112 255.989 112C222.818 112 191.742 123.715 166.276 144.23L200.026 178C217.034 195.018 204.981 224.104 180.921 224.104H35.175C24.585 224.104 16 215.519 16 204.929V59.036C16 34.992 45.074 22.954 62.07 39.961Z"></path></svg>`,
        'redo': `<svg class="svg-inline--fa fa-arrow-rotate-forward fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="arrow-rotate-forward" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M496 47.996V192.004C496 200.844 492.42 208.844 486.631 214.633S472.842 224 464.004 224H319.996C302.326 224 288 209.676 288 192.004C288 174.336 302.324 160.008 319.994 160.008H383.83C383.828 160.008 383.826 160 383.824 160H383.943C353.941 120.094 306.754 96 256 96C167.781 96 96 167.781 96 256S167.781 416 256 416C290.969 416 324.156 404.938 351.969 384.031C369.562 370.75 394.656 374.313 407.969 391.969C421.25 409.625 417.687 434.688 400.031 447.969C358.25 479.406 308.469 496 256 496C123.656 496 16 388.344 16 256S123.656 16 256 16C323.773 16 387.141 44.789 432.008 93.305V47.992C432.008 30.324 446.332 16 464.002 16C481.674 16 496 30.324 496 47.996Z"></path></svg>`,
        'bold': `<svg class="svg-inline--fa fa-bold fa-w-12" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="bold" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg=""><path fill="currentColor" d="M336.609 247.539C355.465 224.709 367.266 195.85 367.266 164C367.266 91.219 308.047 32 235.266 32H40C17.906 32 0 49.906 0 72S17.906 112 40 112H48V400H40C17.906 400 0 417.906 0 440S17.906 480 40 480H252C324.781 480 384 420.781 384 348C384 307.566 365.334 271.771 336.609 247.539ZM128 112H235.266C263.938 112 287.266 135.312 287.266 164S263.938 216 235.266 216H128V112ZM252 400H128V296H252C280.672 296 304 319.312 304 348S280.672 400 252 400Z"></path></svg>`,
        'italic': `<svg class="svg-inline--fa fa-italic fa-w-12" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="italic" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg=""><path fill="currentColor" d="M384 72C384 94.094 366.094 112 344 112H294.223L176.199 400H216C238.094 400 256 417.906 256 440S238.094 480 216 480H40C17.906 480 0 462.094 0 440S17.906 400 40 400H89.777L207.801 112H168C145.906 112 128 94.094 128 72S145.906 32 168 32H344C366.094 32 384 49.906 384 72Z"></path></svg>`,
        'underline': `<svg class="svg-inline--fa fa-underline fa-w-14" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="underline" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M48 64H64V224C64 312.219 135.781 384 224 384S384 312.219 384 224V64H400C417.688 64 432 49.688 432 32S417.688 0 400 0H288C270.312 0 256 14.312 256 32S270.312 64 288 64H304V224C304 268.125 268.125 304 224 304S144 268.125 144 224V64H160C177.688 64 192 49.688 192 32S177.688 0 160 0H48C30.312 0 16 14.312 16 32S30.312 64 48 64ZM416 448H32C14.312 448 0 462.312 0 480S14.312 512 32 512H416C433.688 512 448 497.688 448 480S433.688 448 416 448Z"></path></svg>`,
        'removeFormat': `<svg class="svg-inline--fa fa-eraser fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="eraser" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M497.999 273.986C507.312 264.61 511.969 252.297 511.969 239.983S507.312 215.357 497.999 205.981L337.989 45.97C328.614 36.657 316.301 32 303.987 32S279.361 36.657 269.985 45.97L13.97 301.988C4.657 311.363 0 323.677 0 335.99S4.657 360.617 13.97 369.992L109.975 465.999C118.94 474.964 131.099 480 143.777 480H499.999C506.627 480 512 474.627 512 467.999V467.999C512 439.278 488.717 415.996 459.996 415.996H355.865L497.999 273.986ZM195.356 211.356L332.614 348.616L265.36 415.996H150.603L70.598 335.99L195.356 211.356Z"></path></svg>`,
        'insertOrderedList': `<svg class="svg-inline--fa fa-list-ol fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="list-ol" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M480.001 224H224.001C206.327 224 192.001 238.326 192.001 256S206.327 288 224.001 288H480.001C497.674 288 512.001 273.674 512.001 256S497.674 224 480.001 224ZM224.001 128H480.001C497.674 128 512.001 113.674 512.001 96S497.674 64 480.001 64H224.001C206.327 64 192.001 78.326 192.001 96S206.327 128 224.001 128ZM480.001 384H224.001C206.327 384 192.001 398.326 192.001 416S206.327 448 224.001 448H480.001C497.674 448 512.001 433.674 512.001 416S497.674 384 480.001 384ZM40.001 224H120.001C133.251 224 144.001 213.25 144.001 199.998S133.251 175.996 120.001 175.996H104.001V55.986C104.001 47.393 99.407 39.453 91.938 35.172C84.501 30.906 75.282 30.953 67.907 35.25L35.907 53.924C24.469 60.611 20.594 75.301 27.282 86.754C33.126 96.85 45.313 101.1 56.001 97.287V175.996H40.001C26.751 175.996 16.001 186.746 16.001 199.998S26.751 224 40.001 224ZM136.323 432.002H86.88L123.284 399.52C153.2 373.693 156.819 328.164 131.333 298.023C118.573 282.963 100.669 273.807 80.891 272.244C61.27 270.65 41.928 276.932 26.924 289.867L14.417 300.664C4.372 309.32 3.249 324.477 11.889 334.521C20.561 344.584 35.69 345.662 45.672 337.037L58.182 326.242C63.422 321.711 70.286 319.57 77.147 320.086C84.073 320.633 90.342 323.82 94.772 329.055C103.475 339.334 102.258 354.318 91.715 363.412L8.02 438.08C0.596 444.705 -1.962 455.236 1.563 464.547C5.12 473.844 14.01 480 23.962 480H136.323C149.549 480 160.28 469.25 160.28 456.002C160.28 442.752 149.549 432.002 136.323 432.002Z"></path></svg>`,
        'insertUnorderedList': `<svg class="svg-inline--fa fa-list fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="list" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M80 48H16C7.156 48 0 55.156 0 64V128C0 136.844 7.156 144 16 144H80C88.844 144 96 136.844 96 128V64C96 55.156 88.844 48 80 48ZM80 208H16C7.156 208 0 215.156 0 224V288C0 296.844 7.156 304 16 304H80C88.844 304 96 296.844 96 288V224C96 215.156 88.844 208 80 208ZM80 368H16C7.156 368 0 375.156 0 384V448C0 456.844 7.156 464 16 464H80C88.844 464 96 456.844 96 448V384C96 375.156 88.844 368 80 368ZM192 128H480C497.674 128 512 113.674 512 96S497.674 64 480 64H192C174.326 64 160 78.326 160 96S174.326 128 192 128ZM480 384H192C174.326 384 160 398.326 160 416S174.326 448 192 448H480C497.674 448 512 433.674 512 416S497.674 384 480 384ZM480 224H192C174.326 224 160 238.326 160 256S174.326 288 192 288H480C497.674 288 512 273.674 512 256S497.674 224 480 224Z"></path></svg>`,
        'link': `<svg class="svg-inline--fa fa-link fa-w-20" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="link" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M598.594 41.406C570.991 13.803 534.809 0 498.629 0S426.27 13.803 398.665 41.406L355.309 84.762C370.42 92.773 384.78 102.342 397.219 114.781C400.366 117.928 403.118 121.299 405.961 124.619L443.92 86.66C458.534 72.047 477.963 64 498.629 64S538.727 72.047 553.34 86.662C567.954 101.275 576.002 120.705 576.002 141.371S567.954 181.467 553.34 196.08L420.081 329.338C405.467 343.951 386.038 352 365.372 352S325.276 343.951 310.663 329.336C296.047 314.723 288 295.293 288 274.627S296.047 234.531 310.663 219.918L314.168 216.412C312.075 212.473 309.95 208.518 306.704 205.266C298.141 196.719 286.75 192 274.625 192C262.692 192 251.528 196.664 243.014 204.969C212.299 258.926 219.381 328.566 265.407 374.592C293.012 402.197 329.192 416 365.372 416C401.553 416 437.733 402.197 465.336 374.592L598.594 241.336C627.045 212.885 640.834 175.325 639.962 138.044C639.142 102.981 625.353 68.165 598.594 41.406ZM234.042 387.381L196.083 425.34C181.469 439.953 162.04 448 141.374 448C120.706 448 101.274 439.953 86.661 425.338C72.047 410.725 63.999 391.295 63.999 370.629S72.047 330.533 86.661 315.92L219.922 182.662C234.536 168.049 253.965 160 274.631 160S314.727 168.049 329.34 182.664C343.956 197.277 352.002 216.707 352.002 237.373S343.956 277.469 329.34 292.082L325.834 295.588C327.928 299.527 330.053 303.482 333.299 306.734C341.862 315.281 353.252 320 365.377 320C377.311 320 388.475 315.336 396.989 307.031C427.704 253.074 420.622 183.434 374.596 137.408C346.991 109.803 310.811 96 274.631 96C238.45 96 202.27 109.803 174.667 137.408L41.407 270.664C13.802 298.268 0 334.449 0 370.629C0 406.809 13.802 442.989 41.407 470.594C69.01 498.197 105.192 512 141.374 512C177.553 512 213.733 498.197 241.338 470.594L284.694 427.238C269.583 419.227 255.223 409.658 242.784 397.219C239.637 394.072 236.885 390.701 234.042 387.381Z"></path></svg>`,
        'unlink': `<svg class="svg-inline--fa fa-link-slash fa-w-20" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="link-slash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M485.06 354.867L598.593 241.336C653.802 186.127 653.802 96.615 598.593 41.406C570.989 13.803 534.808 0 498.628 0S426.269 13.803 398.663 41.406L355.308 84.762C370.419 92.773 384.779 102.342 397.218 114.781C400.364 117.928 403.116 121.299 405.96 124.619L443.919 86.66C458.532 72.047 477.962 64 498.628 64S538.726 72.047 553.339 86.662C567.952 101.275 576.001 120.705 576.001 141.371S567.952 181.467 553.339 196.08L434.32 315.098L404.226 291.512C425.712 240.23 416.347 179.16 374.595 137.408C346.989 109.803 310.81 96 274.63 96C245.029 96 215.7 105.752 190.796 124.23L38.814 5.109C34.407 1.672 29.189 0 24.032 0C16.907 0 9.845 3.156 5.126 9.188C-3.061 19.625 -1.249 34.717 9.189 42.889L174.794 172.688L205.685 196.898L328.366 293.055L353.572 312.809L395.657 345.795L401.409 350.303L601.185 506.883C611.685 515.086 626.747 513.211 634.872 502.805C643.06 492.367 641.247 477.273 630.81 469.102L485.06 354.867ZM350.802 249.639L244.349 166.203C253.806 162.178 264.042 160 274.63 160C295.296 160 314.726 168.049 329.339 182.664C343.954 197.277 352.001 216.707 352.001 237.373C352.001 241.539 351.445 245.602 350.802 249.639ZM234.04 387.381L196.081 425.34C181.468 439.953 162.038 448 141.372 448C120.704 448 101.273 439.953 86.659 425.338C72.046 410.725 63.997 391.295 63.997 370.629S72.046 330.533 86.659 315.92L171.488 231.094L120.747 191.326L41.405 270.664C-13.803 325.873 -13.803 415.385 41.405 470.594C69.009 498.197 105.191 512 141.372 512C177.552 512 213.732 498.197 241.337 470.594L284.693 427.238C269.581 419.227 255.222 409.658 242.782 397.219C239.636 394.072 236.884 390.701 234.04 387.381ZM265.405 374.592C293.011 402.197 329.191 416 365.37 416C377.355 416 389.212 413.918 400.884 410.889L224.572 272.699C223.868 309.502 237.275 346.461 265.405 374.592Z"></path></svg>`,
        'img': `<svg class="svg-inline--fa fa-image fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="image" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M448 32H64C28.654 32 0 60.654 0 96V416C0 451.346 28.654 480 64 480H448C483.346 480 512 451.346 512 416V96C512 60.654 483.346 32 448 32ZM112 96C138.51 96 160 117.492 160 144S138.51 192 112 192S64 170.508 64 144S85.49 96 112 96ZM446.121 407.551C443.336 412.754 437.914 416 432.014 416H82.013C75.992 416 70.48 412.621 67.752 407.25C65.021 401.883 65.537 395.438 69.086 390.574L139.086 294.574C142.098 290.441 146.902 288 152.014 288S161.93 290.441 164.941 294.574L197.396 339.086L290.701 199.125C293.668 194.672 298.664 192 304.014 192S314.359 194.672 317.326 199.125L445.326 391.125C448.6 396.035 448.904 402.348 446.121 407.551Z"></path></svg>`,
        'video': `<svg class="svg-inline--fa fa-video fa-w-18" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="video" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M384 112V400C384 426.51 362.51 448 336 448H48C21.49 448 0 426.51 0 400V112C0 85.49 21.49 64 48 64H336C362.51 64 384 85.49 384 112ZM576 127.5V384.406C576 409.906 546.812 424.797 525.594 410.203L416 334.703V177.297L525.594 101.703C546.906 87.094 576 102.094 576 127.5Z"></path></svg>`,
        'justifyLeft': `<svg class="svg-inline--fa fa-align-left fa-w-14" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="align-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 416H32C14.326 416 0 430.326 0 448S14.326 480 32 480H416C433.674 480 448 465.674 448 448S433.674 416 416 416ZM32 224H416C433.674 224 448 209.674 448 192S433.674 160 416 160H32C14.326 160 0 174.326 0 192S14.326 224 32 224ZM32 96H256C273.674 96 288 81.674 288 64S273.674 32 256 32H32C14.326 32 0 46.326 0 64S14.326 96 32 96ZM32 352H256C273.674 352 288 337.674 288 320S273.674 288 256 288H32C14.326 288 0 302.326 0 320S14.326 352 32 352Z"></path></svg>`,
        'justifyCenter': `<svg class="svg-inline--fa fa-align-center fa-w-14" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="align-center" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 160H32C14.327 160 0 174.327 0 192V192C0 209.673 14.327 224 32 224H416C433.673 224 448 209.673 448 192V192C448 174.327 433.673 160 416 160ZM416 416H32C14.327 416 0 430.327 0 448V448C0 465.673 14.327 480 32 480H416C433.673 480 448 465.673 448 448V448C448 430.327 433.673 416 416 416ZM128 96H320C337.673 96 352 81.673 352 64V64C352 46.327 337.673 32 320 32H128C110.327 32 96 46.327 96 64V64C96 81.673 110.327 96 128 96ZM320 352C337.673 352 352 337.673 352 320V320C352 302.327 337.673 288 320 288H128C110.327 288 96 302.327 96 320V320C96 337.673 110.327 352 128 352H320Z"></path></svg>`,
        'justifyRight': `<svg class="svg-inline--fa fa-align-right fa-w-14" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="align-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M0 448C0 465.674 14.326 480 32 480H416C433.674 480 448 465.674 448 448S433.674 416 416 416H32C14.326 416 0 430.326 0 448ZM448 192C448 174.326 433.674 160 416 160H32C14.326 160 0 174.326 0 192S14.326 224 32 224H416C433.674 224 448 209.674 448 192ZM448 64C448 46.326 433.674 32 416 32H192C174.326 32 160 46.326 160 64S174.326 96 192 96H416C433.674 96 448 81.674 448 64ZM448 320C448 302.326 433.674 288 416 288H192C174.326 288 160 302.326 160 320S174.326 352 192 352H416C433.674 352 448 337.674 448 320Z"></path></svg>`,
        'html': `<svg class="svg-inline--fa fa-code fa-w-20" aria-hidden="true" focusable="false" data-prefix="fa-solid" data-icon="code" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M181.312 124.781C166.297 108.594 140.984 107.656 124.781 122.688L12.781 226.688C4.625 234.25 0 244.875 0 256S4.625 277.75 12.781 285.312L124.781 389.312C132.484 396.469 142.25 400 151.984 400C162.719 400 173.438 395.719 181.312 387.219C196.344 371.031 195.406 345.719 179.219 330.687L98.781 256L179.219 181.312C195.406 166.281 196.344 140.969 181.312 124.781ZM627.219 226.688L515.219 122.688C499.031 107.656 473.703 108.594 458.687 124.781C443.656 140.969 444.594 166.281 460.781 181.313L541.219 256L460.781 330.688C444.594 345.719 443.656 371.031 458.687 387.219C466.562 395.719 477.281 400 488.016 400C497.75 400 507.516 396.469 515.219 389.312L627.219 285.312C635.375 277.75 640 267.125 640 256S635.375 234.25 627.219 226.688ZM386.031 1.281C364.75 -4.25 342.828 8.594 337.281 29.969L225.281 461.969C219.734 483.344 232.578 505.188 253.969 510.719C257.328 511.594 260.703 512 264.031 512C281.813 512 298.047 500.062 302.719 482.031L414.719 50.031C420.266 28.656 407.422 6.812 386.031 1.281Z"></path></svg>`
    };

    // Criando os elementos 
    var container = createElementWithAttributes('div', { class: 'container' });
    var toolbar = createElementWithAttributes('div', { class: 'toolbar' });

    toolbar.appendChild(createButton(buttons.undo, 'button', { 'aria-text': 'undo' }));
    toolbar.appendChild(createButton(buttons.redo, 'button', { 'aria-text': 'redo' }));
    toolbar.appendChild(createButton(buttons.bold, 'button', { 'aria-text': 'bold' }));
    toolbar.appendChild(createButton(buttons.italic, 'button', { 'aria-text': 'italic' }));
    toolbar.appendChild(createButton(buttons.underline, 'button', { 'aria-text': 'underline' }));
    toolbar.appendChild(createButton(buttons.removeFormat, 'button', { 'aria-text': 'removeFormat' }));

    // Criar fontName
    var fontSelect = createElementWithAttributes('select', { 'aria-text': 'fontName', 'aria-valuetext': 'false' });
    var fonts = ['Arial', 'Verdana', 'Tahoma', 'Georgia', 'Times New Roman', 'Courier New', 'Lucida Console', 'Impact', 'Comic Sans MS'];
    fonts.forEach(function (font) {
        var option = createElementWithAttributes('option', { value: font });
        option.textContent = font;
        fontSelect.appendChild(option);
    });
    toolbar.appendChild(fontSelect);

    // Criar fontSize
    var fontSelectSize = createElementWithAttributes('select', { 'aria-text': 'fontSize', 'aria-valuetext': 'false' });
    var fontsSize = [];
    for (let i = 8; i <= 36; i++) {
        if (i % 2 == 0) fontsSize.push(i);
    }
    fontsSize.forEach(function (font) {
        var option = createElementWithAttributes('option', { value: font });
        option.textContent = font;
        fontSelectSize.appendChild(option);
    });
    toolbar.appendChild(fontSelectSize);

    // Criar Titulos
    var titulos = createElementWithAttributes('select', { 'aria-text': 'formatBlock', 'aria-valuetext': 'false' });
    var tituloArray = [1, 2, 3, 4, 5, 6];
    tituloArray.forEach(function (titulo) {
        var option = createElementWithAttributes('option', { value: '<h' + titulo + '>' });
        option.textContent = 'Título ' + titulo;
        titulos.appendChild(option);
    });
    toolbar.appendChild(titulos);

    // Criar os outros botoes
    toolbar.appendChild(createButton(buttons.insertOrderedList, 'button', { 'aria-text': 'insertOrderedList' }));
    toolbar.appendChild(createButton(buttons.insertUnorderedList, 'button', { 'aria-text': 'insertUnorderedList' }));
    toolbar.appendChild(createButton(buttons.link, 'button', { 'aria-text-click': 'link' }));
    toolbar.appendChild(createButton(buttons.unlink, 'button', { 'aria-text': 'unlink' }));

    toolbar.appendChild(createButton(buttons.justifyLeft, 'button', { 'aria-text': 'justifyLeft' }));
    toolbar.appendChild(createButton(buttons.justifyCenter, 'button', { 'aria-text': 'justifyCenter' }));
    toolbar.appendChild(createButton(buttons.justifyRight, 'button', { 'aria-text': 'justifyRight' }));

    toolbar.appendChild(createButton(buttons.html, 'button', { 'aria-text-click': 'html' }));


    // Criar ocoes de cores
    const arrayColors = [
        { title: 'Cor da fonte', span: 'Texto:', aria: 'foreColor' },
        { title: 'Cor do realce da fonte', span: 'Realce:', aria: 'backColor' }
    ];

    arrayColors.forEach(element => {
        var colors = document.createElement('label');
        colors.className = 'colors';
        colors.title = element.title;

        var span = document.createElement('span');
        span.textContent = element.span;
        colors.appendChild(span);

        var input = document.createElement('input');
        input.type = 'color';
        input.setAttribute('aria-text', element.aria);
        input.setAttribute('aria-valuetext', 'false');
        colors.appendChild(input);

        toolbar.appendChild(colors);
    });


    container.appendChild(toolbar);

    var editable = createElementWithAttributes('div', { class: 'editable', contenteditable: 'true', id: 'editor' });
    container.appendChild(editable);

    // Adicionar ao form
    var textBox = document.querySelector(id);
    textBox.appendChild(container);

    // Chamar funcoes essenciais
    buttonsActions();

    // Adicionar a input os valores
    var inputEditable = document.getElementById('description');
    setInterval(() => {
        inputEditable.value = editable.innerHTML;
    }, 100);
}

// ----------------------------Funcoes-------------------------- 

// Funcoes dos botões
function buttonsActions() {
    var aria_text = document.querySelectorAll('[aria-text]');
    aria_text.forEach(element => {
        var ariaText = element.getAttribute("aria-text");

        if (!element.getAttribute("aria-valuetext")) {
            element.addEventListener('click', () => {
                document.execCommand(ariaText, false, null);
            });
        } else {
            element.addEventListener('change', () => {
                document.execCommand(ariaText, false, element.value);
            });
        }
    });

    var aria_text_click = document.querySelectorAll('[aria-text-click]');
    aria_text_click.forEach(element => {
        var ariaAttribute = element.getAttribute('aria-text-click');
        element.addEventListener('click', () => {
            switch (ariaAttribute) {
                case 'link':
                    link();
                    break;
                case 'html':
                    var html = document.getElementById('editor').innerHTML;
                    alert(html);
                    break;

                default:
                    break;
            }
        });
    });
}

// Create link function 
function link() {
    var selectedText = "";
    if (window.getSelection) {
        selectedText = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        selectedText = document.selection.createRange().text;
    }

    // Verificar se e http ou https
    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocolo
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domínio
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OU endereço IP
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // porta e caminho
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // parâmetros de consulta
        '(\\#[-a-z\\d_]*)?$', 'i'); // fragmento

    if (!!pattern.test(selectedText)) {
        var linkHTTP = selectedText.split(':')[0].toUpperCase();
        if (linkHTTP == 'HTTPS' || linkHTTP == 'HTTP') document.execCommand('createLink', false, selectedText);
        else document.execCommand('createLink', false, 'http://' + selectedText);

    }
    else document.execCommand('createLink', false, prompt('Enter a URL:', 'http://'))
}

// Adicionar imagem
document.querySelector('#new_image').addEventListener('click', insertData);

// Adicionar o listener de 'change' uma vez
var fileInput = document.getElementById('fileInput');
fileInput.addEventListener('change', handleFileSelect);

// Inserir dados ao selecionar o arquivo
function insertData() {
    fileInput.click();
}

// Função que lida com a seleção de arquivo
function handleFileSelect() {
    var files = fileInput.files; // Obter a lista de arquivos
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                // Exibir imagem
                var fileData = document.createElement('img');
                fileData.src = event.target.result;
                fileData.alt = 'image';
                fileData.className = 'added_data';

                // criar inputs de checkbox
                var inputData = document.createElement('input');
                inputData.value = event.target.result;
                inputData.type = 'checkbox';
                inputData.checked = true;
                inputData.hidden = true;
                inputData.name = 'images[]';

                // Adicionar a pagina
                document.getElementById('added').appendChild(fileData);
                document.getElementById('added').appendChild(inputData);
            };
            reader.readAsDataURL(file);
        }
    }
}

// Função para criar elementos e adicionar atributos
function createElementWithAttributes(tagName, attributes) {
    var element = document.createElement(tagName);
    for (var key in attributes) {
        element.setAttribute(key, attributes[key]);
    }
    return element;
}

// Função para criar botões
function createButton(text, type, attributes) {
    var button = createElementWithAttributes('button', attributes);
    button.setAttribute('type', type);
    button.innerHTML = text;
    return button;
}