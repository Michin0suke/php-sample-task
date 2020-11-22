const DropAreaFunc = (dropArea) => {
    return {
        reset(e) {
            e.preventDefault()
            dropFiles = []
            dropArea.style.backgroundColor = 'white'
            dropArea.style.backgroundImage = ''
            dropArea.innerHTML = 'ファイルを追加してください。'
        },
        changeToHoverColor(e) {
            e.preventDefault()
            dropArea.style.backgroundColor = '#FAFAFA'
        },
        changeToNoHoverColor() {
            dropArea.style.backgroundColor = 'white'
        },
        showImageIfTypeIsImage(file) {
            if (!file.type.includes('image')) return
            const reader = new FileReader()
            reader.onloadend = e => dropArea.style.backgroundImage = `url('${e.target.result}')`
            reader.readAsDataURL(file)
        },
        showFileNames(files) {
            dropArea.innerHTML = ''
            files.forEach(file => dropArea.innerHTML += file.name + '<br>')
        }
    }
}

const formSubmit = (formElement, file) => {
    const inputForms = [...document.getElementsByClassName('input-form')]
    const form = document.createElement('form')
    form.action = formElement.action
    form.method = formElement.method
    form.enctype = 'multipart/form-data'
    form.addEventListener('formdata', e => {
        e.formData.append('file', file)
        inputForms.forEach(input => {
            console.log(input)
            e.formData.append(input.name, input.value)
        })
    })
    document.body.appendChild(form)
    form.submit()
}

const dragAndDrop = () => {
    const errorMessage = document.getElementById('error-message')
    const form = document.querySelector('form#file-upload-form')
    const uploadButton = document.getElementById('file-upload-button')
    const dropArea = document.getElementById('drop-area')
    const dropAreaFunc = DropAreaFunc(dropArea)

    if (!errorMessage || !form || !dropArea || !uploadButton) {
        console.log('HTMLが対応していません。(components/drag_and_drop.js)')
        return
    }

    let dropFiles = []

    dropArea.ondragover = e => dropAreaFunc.changeToHoverColor(e)
    dropArea.ondragleave = () => dropAreaFunc.changeToNoHoverColor()

    dropArea.ondrop = e => {
        dropAreaFunc.reset(e)
        dropFiles = [...e.dataTransfer.files]
        if (dropFiles.length > 1) {
            errorMessage.innerHTML = 'ファイルは一つだけしかアップロードできません。'
            return
        }
        errorMessage.innerHTML = ''
        dropAreaFunc.showFileNames(dropFiles)
        dropAreaFunc.showImageIfTypeIsImage(dropFiles[0])
    }

    uploadButton.onclick = e => {
        e.preventDefault()
        formSubmit(form, dropFiles[0])
    }

    // 直接クリックでファイルを追加された場合
    document.getElementById('file').onchange = (e) => dropAreaFunc.showImageIfTypeIsImage(e.target.files[0])
}

dragAndDrop()