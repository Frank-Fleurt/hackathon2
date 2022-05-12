const clientMessage = document.getElementById('clientMessage')

const clientPopupContainer = document.getElementById('clientPopupContainer')

const definitiveDelete = document.getElementById('definitiveDelete')
const archive = document.getElementById('archive')
const unarchive = document.getElementById('unarchive')
const closePopup = document.getElementById('closePopup')

let clientId = 0
let clientName = ""

function actionButtons(url, alerteMessage){
	let clientCard = document.querySelector(`#clientCard${clientId}`)
	let data = {
		'clientId': clientId
	}
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify(data)
	}).then(()=>{
		eventId = 0
		clientMessage.innerHTML = ""
		clientPopupContainer.style.display = 'none'
		clientCard.remove()
		alert(alerteMessage)
	})
}

let deleteButton = document.querySelectorAll('.actionsButton')

for(i = 0; i < deleteButton.length ; i++){
	deleteButton[i].addEventListener('click', (e)=> {
		clientId = e.target.dataset.id
		clientName = e.target.dataset.name
		clientMessage.innerHTML = "Que voulez-vous faire de " + clientName
		clientPopupContainer.style.display = 'flex'
	} )
}

definitiveDelete.addEventListener('click', () =>{
	let url = `delete/${clientId}`
	actionButtons(url, 'Client supprimer définitivement')
})

if(archive){
	archive.addEventListener('click', () =>{
		let url = `disable/${clientId}`
		actionButtons(url, 'Client bien archiver')
	})
}else if(unarchive){
	unarchive.addEventListener('click', () =>{
		let url = `restaure/${clientId}`
		actionButtons(url, 'Client bien restauré')
	})
}

closePopup.addEventListener('click', () => clientPopupContainer.style.display = 'none')



