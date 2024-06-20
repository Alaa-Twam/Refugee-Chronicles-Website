@extends('frontend.layouts.master')

@section('content')
	<section class="page-title">
		<div class="container">
			<div class="page-title-row">
				<div class="page-title-content">
					<h1>About Us</h1>
				</div>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">About Us</li>
					</ol>
				</nav>
			</div>
		</div>
	</section>
	<section id="content">
		<div class="content-wrap">
			<div class="container">
				<div class="row col-mb-50 mb-0">
					<div class="col-lg-12">
						<div class="about-us-image" style="background-image: url({{ asset('frontend/images/about-us.jpg') }} ) "></div>
					</div>
					<div class="col-lg-12">
						<p>
							Welcome to Refugee Chronicles, a project born out of a deep commitment to preserving the untold stories of the Nakba and ensuring that the voices of its survivors are heard and remembered. Having spent a significant portion of my life in the diaspora, I couldn't help but notice the lack of awareness about the Nakba in the Western world—a period of forced expulsion of Palestinians from Palestine between 1947 and 1949.
						</p>
						<p>
							Refugee Chronicles is not just a project; it's a journey to collect and share the testimonials of elders who personally experienced the Nakba. Venturing into refugee camps in the MENA region, we have had the privilege of hearing these survivors generously open up about their experiences, casting light on voices that have long been unheard. The urgency to archive and protect these stories for history became the driving force behind this initiative.
						</p>
						<p>
							Central to our project is an interactive website featuring a map of Palestine that allows users to explore locations profoundly impacted by the Nakba. Every click on the map unveils poignant survivor accounts, narrating the tales of vanished villages and altered lives. The interactive platform provides immediate access to real-time testimonials from Nakba survivors linked to specific villages or cities.
						</p>
						<p>
							Our website serves as a home for these stories to flourish, offering a space where the past connects with the present. By documenting the stories of Nakba survivors, Refugee Chronicles aims to connect the dots between the past and the present, highlighting the enduring effects of displacement, grief, and injustice. These firsthand accounts serve as a stark reminder that the impact of the first Nakba resonates in the lives of Palestinians today.
						</p>
						<p>
							As time passes, the urgency to record and protect these stories becomes even more crucial, especially considering that the current generation represents the last witnesses of the Nakba. We believe it is our responsibility to archive these stories—a crucial piece of history that needs preservation, particularly in the face of denial that it ever occurred.
						</p>
						<p>
							Join us on this meaningful journey of remembrance, connection, and the pursuit of justice.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection