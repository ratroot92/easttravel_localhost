@extends('layouts.admin')
<script src="{{url('ckeditor/ckeditor.js')}}"></script>
@section('content')
    <div class="sb2-2">
        <div class="sb2-2-2">
            <ul>
<li><a href="index.html"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/terms')}}"> Terms and Consitions</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/cancellations')}}"> Cancellations Policy</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/contactus')}}"> Contact Us</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/cookies')}}"> Cookie Policy</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/paymentpolicy')}}"> Cookie Policy</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/faq')}}"> FAQs</a></li>
<li class="active-bre"><a href="{{url('websitebuilder/aboutus')}}"> About Us</a></li>

                <li class="page-back"><a href="{{url('/websitebuilder')}}"><i class="fa fa-backward" aria-hidden="true"></i> Back</a>
                </li>
            </ul>
        </div>
        <div class="sb2-2-add-blog sb2-2-1">
            <div class="box-inn-sp">
                <div class="inn-title">
                    <h4> FAQ</h4>
                
                </div>
                <div class="bor">
                <form method="post"  action="{{url('websitebuilder/updateFaq')}}">
                        {{csrf_field()}}
                       
                           <div class="row">
                            <br>
                            <label for="textarea1" style="color:red;font-weight:bold;font-size:14px;">UPDATE FAQs</label>
                            <div class="input-field col s12">
                             <textarea id="content" class="ckeditor" name="content" required="required" >{{$content->faq}}</textarea>
                            </div>
                            </div>




                             <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light btn-large">UPDATE FAQs <i class="fa fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>





                      

                        
                      



                    
                </div>
            </div>
        </div>
    </div>

@endsection
