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
	}).then((res)=>{
		console.log(res)
		if(res.ok === true){
			eventId = 0
			clientMessage.innerHTML = ""
			clientPopupContainer.style.display = 'none'
			clientCard.remove()
			alert(alerteMessage)
		}else{
			alert('Le client n\'a pas pu être supprimé veuillez recharger la page.')
		}
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
	actionButtons(url, 'Client supprimé définitivement.')
})

if(archive){
	archive.addEventListener('click', () =>{
		let url = `disable/${clientId}`
		actionButtons(url, 'Client bien archivé.')
	})
}else if(unarchive){
	unarchive.addEventListener('click', () =>{
		let url = `restaure/${clientId}`
		actionButtons(url, 'Client bien restauré.')
	})
}

closePopup.addEventListener('click', () => clientPopupContainer.style.display = 'none')



