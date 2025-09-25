<!--start switcher-->
<div class="switcher-wrapper">
	<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
	</div>
	<div class="switcher-body">
		<div class="d-flex align-items-center">
			<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
			<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
		</div>
		<hr/>
		<h6 class="mb-0">Theme Styles</h6>
		<hr/>
		<div class="d-flex align-items-center justify-content-between">
			<div class="form-check">
				<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode">
				<label class="form-check-label" for="lightmode">Light</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
				<label class="form-check-label" for="darkmode">Dark</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
				<label class="form-check-label" for="semidark">Semi Dark</label>
			</div>
		</div>
		<hr/>
		<div class="form-check">
			<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
			<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
		</div>
		<hr/>
<!-- 					<h6 class="mb-0">Header Colors</h6>
					<hr/>
					<div class="header-colors-indigators">
						<div class="row row-cols-auto g-3">
							<div class="col">
								<div class="indigator headercolor1" id="headercolor1"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor2" id="headercolor2"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor3" id="headercolor3"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor4" id="headercolor4"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor5" id="headercolor5"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor6" id="headercolor6"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor7" id="headercolor7"></div>
							</div>
							<div class="col">
								<div class="indigator headercolor8" id="headercolor8"></div>
							</div>
						</div>
					</div>
					<hr/> -->
					<h6 class="mb-0">Sidebar Colors</h6>
					<hr/>
					<div class="header-colors-indigators">
						<div class="row row-cols-auto g-3">
							<div class="col">
								<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
							</div>
							<div class="col">
								<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<script>


				// document.getElementById("lightmode").addEventListener("click", changeThemes);
				// document.getElementById("darkmode").addEventListener("click", changeThemes);
				// document.getElementById("semidark").addEventListener("click", changeThemes);
				// document.getElementById("minimaltheme").addEventListener("click", changeThemes);

				// document.getElementById("sidebarcolor1").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor2").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor3").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor4").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor5").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor6").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor7").addEventListener("click", myFunction);
				// document.getElementById("sidebarcolor8").addEventListener("click", myFunction);

				// function changeThemes() {
				// 	alert("YOU CHANGE THEME!");
				// }

				// function myFunction() {
				// 	alert("YOU CLICKED ME!");
				// }

				const htmlElement = document.documentElement;

				function onClassChange(mutations) {

					let className = null
					mutations.forEach(mutation => {
						if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
							className = htmlElement.className;
						}
					});

					httpRequestFireAndForget("{{url('post-set-data-themes') }}", 'POST', {
						body: className
					});
				}

				const observer = new MutationObserver(onClassChange);

				observer.observe(htmlElement, {
					attributes: true, 
					attributeFilter: ['class'] 
				});



				function httpRequestFireAndForget(url, method = 'POST', body = null) {
					const options = {
						method: method,
						headers: {
							'Content-Type': 'application/json'
						},
					};

					if (body) {
						options.body = JSON.stringify(body);
					}

					fetch(url, options)
					.then(response => {
						if (!response.ok) {
							console.error(`HTTP error: ${response.status}`);
						}
					})
					.catch(error => {
						console.error('Request failed', error);
					});

					console.log('Request fired, but not waiting for the response');
				}

				
			</script>

			<script type="text/javascript">
				function checkHtmlClass() {

					const htmlElement = document.documentElement;


					const classList = htmlElement.className;


					if (classList.includes('light-theme')) {
						document.getElementById('lightmode').checked = true;

					} else if (classList.includes('dark-theme')) {
						document.getElementById('darkmode').checked = true;

					} else if (classList.includes('semi-dark')) {
						document.getElementById('semidark').checked = true;
						
					}  else if (classList.includes('minimal-theme')) {
						document.getElementById('minimaltheme').checked = true;
					} 
				}


				window.onload = checkHtmlClass;
			</script>
			
			<!--end switcher-->