<div class="row">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    24/7 Automatic Gateway

                    <div class="d-inline float-right">
                        <span class="fas fa-circle chat-online"></span>
                        Online
                    </div>

                </h5>


            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>BIN</th>
                </tr>
                </thead>
                <tbody class="text-end">
                <tr>
                    <td>1</td>

                    <td>451129</td>
                </tr>
                <tr>
                    <td>2</td>

                    <td>491277</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    Manually Processed Gateway
                    <div class="d-inline float-right">
                        @if($customer_status)
                            <span class="fas fa-circle chat-online"></span>
                            Online
                            @else
                            <span class="fas fa-circle chat-offline"></span>
                            Offline
                        @endif
                    </div>
                </h5>
            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>BIN</th>
                </tr>
                </thead>
                <tbody class="text-end">
                <tr>
                    <td>1</td>

                    <td>411810</td>
                </tr>

                <tr>
                    <td>2</td>

                    <td>510404</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="card">
            {{--            <div class="card-header">--}}
            {{--                <h5 class="card-title">Alerts with buttons</h5>--}}
            {{--                <h6 class="card-subtitle text-muted">Alerts with actions.</h6>--}}
            {{--            </div>--}}
            <div class="card-body">

                <div class="mb-3">
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <div class="alert-message">
                            <h4 class="alert-heading">Good Card Declined?</h4>
                            <p>
                                If a card was good and was still declined enter the card information here and upload a
                                screenshot so our support can review it.
                            </p>
                            <hr>
                            <div class="btn-list">
                                <button class="btn btn-success" data-toggle="modal" data-target="#myModal"
                                        type="button">Send card info
                                </button>
                                <button class="btn btn-danger" type="button">No, thanks</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-3"></div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('feedbacks.store') }}">
                        @csrf
                        @method('POST')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Card Feedback</h5>

                        </div>

                        <div class="modal-body m-3">
                            <div class="form-group">
                                <label for="number">Card Number</label>
                                <input
                                    class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                    type="number"
                                    name="card_number"
                                    placeholder="Enter card number"
                                    value="{{ old('card_number' )}}"
                                />

                                @error('card_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="row" style="">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="month">Month</label>
                                        <input
                                            class="form-control form-control-lg @error('month') is-invalid @enderror"
                                            type="text"
                                            name="month"
                                            placeholder="MM"
                                            min="01"
                                            max="12"
                                            minlength="2"
                                            maxlength="2"
                                            value="{{ old('month') }}"
                                        />
                                        @error('month')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <input
                                            class="form-control form-control-lg @error('year') is-invalid @enderror"
                                            type="text"
                                            name="year"
                                            min="21"
                                            max="99"
                                            minlength="2"
                                            maxlength="4"
                                            placeholder="YY"
                                            value="{{ old('year') }}"
                                        />
                                        @error('year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cvc">CVC</label>
                                        <input
                                            class="form-control form-control-lg @error('cvc') is-invalid @enderror"
                                            type="text"
                                            name="cvc"
                                            maxlength="4"
                                            minlength="3"
                                            placeholder="XXX"
                                            value="{{ old('cvc') }}"
                                        />
                                        @error('cvc')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            <div class="form-group">
                                <label for="year">Amount ($)</label>
                                <input
                                    class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                    type="number"
                                    name="amount"
                                    min="0.01"
                                    max="500"
                                    step="0.01"
                                    placeholder="Enter amount"
                                    value="{{ old('amount' )}}"
                                />
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                                <div class="form-group">
                                    <textarea
                                        class="form-control text-area-description"
                                        name="user_note"
                                        placeholder="Put your card info and also paste image here">
                                        <code>
                                        <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">
    <head>
        <title></title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <br />
        <style type="text/css">
            <!--
            	p {margin: 0; padding: 0;}	.ft10{font-size:9px;font-family:Times;color:#ffffff;}
            	.ft11{font-size:9px;font-family:Times;color:#000000;}
            	.ft12{font-size:15px;font-family:Times;color:#464646;}
            	.ft13{font-size:11px;font-family:Times;color:#464646;}
            	.ft14{font-size:11px;font-family:Times;color:#000000;}
            	.ft15{font-size:10px;font-family:Times;color:#000000;}
            	.ft16{font-size:10px;font-family:Times;color:#ffffff;}
            -->
        </style>
    </head>
    <body bgcolor="#A0A0A0" vlink="blue" link="blue">
        <div id="page1-div" style="position: relative; width: 465px; height: 2249px;">
            <img width="465" height="2249" src="https://images.pexels.com/photos/3738673/pexels-photo-3738673.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=250&w=1260" alt="background image" />
            <p style="position: absolute; top: 664px; left: 233px; white-space: nowrap;" class="ft10">&#160;</p>
            <p style="position: absolute; top: 1398px; left: 232px; white-space: nowrap;" class="ft11">&#160;</p>
            <p style="position: absolute; top: 374px; left: 119px; white-space: nowrap;" class="ft12">Escanea&#160;el&#160;código&#160;QR&#160;</p>
            <p style="position: absolute; top: 464px; left: 123px; white-space: nowrap;" class="ft12">Escoge&#160;tu&#160;descuento&#160;y&#160;descárgalo</p>
            <p style="position: absolute; top: 559px; left: 123px; white-space: nowrap;" class="ft12">Presenta&#160;tu&#160;descuento&#160;en&#160;el&#160;establecimiento.</p>
            <p style="position: absolute; top: 315px; left: 76px; white-space: nowrap;" class="ft12">¿CÓMO&#160;CONSEGUIR&#160;TU&#160;DESCUENTO?</p>
            <p style="position: absolute; top: 711px; left: 82px; white-space: nowrap;" class="ft13"><b>5&#160;ÀSEC</b></p>
            <p style="position: absolute; top: 767px; left: 79px; white-space: nowrap;" class="ft13"><b>BOBOLI</b></p>
            <p style="position: absolute; top: 696px; left: 221px; white-space: nowrap;" class="ft14"><b>20%&#160;de&#160;descuento,&#160;aplicable&#160;sobre&#160;la</b></p>
            <p style="position: absolute; top: 713px; left: 215px; white-space: nowrap;" class="ft14"><b>limpieza&#160;y&#160;planchado&#160;de&#160;trajes,&#160;camisas</b></p>
            <p style="position: absolute; top: 731px; left: 270px; white-space: nowrap;" class="ft14"><b>unitarias&#160;y&#160;colada</b></p>
            <p style="position: absolute; top: 761px; left: 214px; white-space: nowrap;" class="ft14"><b>Regalo&#160;monedero&#160;por&#160;compras&#160;superiores</b></p>
            <p style="position: absolute; top: 779px; left: 300px; white-space: nowrap;" class="ft14"><b>a&#160;100€</b></p>
            <p style="position: absolute; top: 811px; left: 87px; white-space: nowrap;" class="ft13"><b>BUFF</b></p>
            <p style="position: absolute; top: 812px; left: 269px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 849px; left: 76px; white-space: nowrap;" class="ft13"><b>BUVETTE</b></p>
            <p style="position: absolute; top: 844px; left: 214px; white-space: nowrap;" class="ft14"><b>Por&#160;compras&#160;superiores&#160;a&#160;20€,&#160;1&#160;cookie</b></p>
            <p style="position: absolute; top: 861px; left: 275px; white-space: nowrap;" class="ft14"><b>sabor&#160;a&#160;escoger.</b></p>
            <p style="position: absolute; top: 895px; left: 80px; white-space: nowrap;" class="ft13"><b>CEBADO</b></p>
            <p style="position: absolute; top: 896px; left: 238px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento&#160;en&#160;servicios</b></p>
            <p style="position: absolute; top: 930px; left: 71px; white-space: nowrap;" class="ft13"><b>CHOCOLAT</b></p>
            <p style="position: absolute; top: 947px; left: 77px; white-space: nowrap;" class="ft13"><b>FACTORY</b></p>
            <p style="position: absolute; top: 934px; left: 218px; white-space: nowrap;" class="ft14"><b>Por&#160;compras&#160;superiores&#160;a&#160;60€,&#160;regalo</b></p>
            <p style="position: absolute; top: 951px; left: 280px; white-space: nowrap;" class="ft14"><b>acetato&#160;80&#160;gr</b></p>
            <p style="position: absolute; top: 991px; left: 83px; white-space: nowrap;" class="ft13"><b>COTTET</b></p>
            <p style="position: absolute; top: 981px; left: 234px; white-space: nowrap;" class="ft14"><b>Regalo&#160;de&#160;bolsa&#160;de&#160;algodón&#160;120</b></p>
            <p style="position: absolute; top: 998px; left: 217px; white-space: nowrap;" class="ft14"><b>aniversario,&#160;por&#160;una&#160;compra&#160;superior&#160;a</b></p>
            <p style="position: absolute; top: 1016px; left: 303px; white-space: nowrap;" class="ft14"><b>99€</b></p>
            <p style="position: absolute; top: 1043px; left: 57px; white-space: nowrap;" class="ft13"><b>ESTABLIMENTS</b></p>
            <p style="position: absolute; top: 1061px; left: 76px; white-space: nowrap;" class="ft13"><b>ANDREU</b></p>
            <p style="position: absolute; top: 1042px; left: 215px; white-space: nowrap;" class="ft14"><b>5%&#160;de&#160;descuento,&#160;en&#160;nuestras&#160;cajas&#160;de</b></p>
            <p style="position: absolute; top: 1059px; left: 241px; white-space: nowrap;" class="ft14"><b>jamón&#160;Andreu&#160;Cata&#160;y&#160;Unics</b></p>
            <p style="position: absolute; top: 1101px; left: 66px; white-space: nowrap;" class="ft13"><b>FALCONERI</b></p>
            <p style="position: absolute; top: 1092px; left: 222px; white-space: nowrap;" class="ft14"><b>Regalo&#160;de&#160;unas&#160;pashmina&#160;exclusiva&#160;de</b></p>
            <p style="position: absolute; top: 1109px; left: 218px; white-space: nowrap;" class="ft14"><b>cashmere,&#160;por&#160;compra&#160;superior&#160;a&#160;250€</b></p>
            <p style="position: absolute; top: 1172px; left: 89px; white-space: nowrap;" class="ft13"><b>FNAC</b></p>
            <p style="position: absolute; top: 1147px; left: 208px; white-space: nowrap;" class="ft14"><b>5%&#160;de&#160;descuento&#160;directo&#160;en&#160;determinados</b></p>
            <p style="position: absolute; top: 1165px; left: 237px; white-space: nowrap;" class="ft14"><b>productos,&#160;música,cine,&#160;libros,</b></p>
            <p style="position: absolute; top: 1182px; left: 219px; white-space: nowrap;" class="ft14"><b>videojuegos&#160;(no&#160;consolas),&#160;papelería&#160;y</b></p>
            <p style="position: absolute; top: 1200px; left: 293px; white-space: nowrap;" class="ft14"><b>juguetes</b></p>
            <p style="position: absolute; top: 1238px; left: 92px; white-space: nowrap;" class="ft13"><b>GAP</b></p>
            <p style="position: absolute; top: 1233px; left: 220px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento,&#160;no&#160;acumulable&#160;a</b></p>
            <p style="position: absolute; top: 1250px; left: 240px; white-space: nowrap;" class="ft14"><b>otras&#160;ofertas&#160;o&#160;promociones</b></p>
            <p style="position: absolute; top: 1286px; left: 79px; white-space: nowrap;" class="ft13"><b>JAMAICA</b></p>
            <p style="position: absolute; top: 1289px; left: 264px; white-space: nowrap;" class="ft14"><b>Café&#160;+&#160;tarta&#160;a&#160;4,50€</b></p>
            <p style="position: absolute; top: 1337px; left: 83px; white-space: nowrap;" class="ft13"><b>KIEHL'S</b></p>
            <p style="position: absolute; top: 1330px; left: 221px; white-space: nowrap;" class="ft14"><b>Regalo&#160;por&#160;compras&#160;superior&#160;a&#160;60€</b></p>
            <p style="position: absolute; top: 1348px; left: 240px; white-space: nowrap;" class="ft14"><b>(regalo&#160;2&#160;mini&#160;tallas&#160;Deluxe)</b></p>
            <p style="position: absolute; top: 1392px; left: 75px; white-space: nowrap;" class="ft13"><b>LAS&#160;MUNS</b></p>
            <p style="position: absolute; top: 1391px; left: 267px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 1440px; left: 266px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 1437px; left: 76px; white-space: nowrap;" class="ft13"><b>LINDSEY</b></p>
            <p style="position: absolute; top: 1486px; left: 90px; white-space: nowrap;" class="ft13"><b>MAC</b></p>
            <p style="position: absolute; top: 1476px; left: 217px; white-space: nowrap;" class="ft14"><b>35€&#160;servicio&#160;express&#160;gratuito&#160;+&#160;bounce</b></p>
            <p style="position: absolute; top: 1493px; left: 216px; white-space: nowrap;" class="ft14"><b>back&#160;card&#160;15%&#160;off&#160;para&#160;próxima&#160;compra</b></p>
            <p style="position: absolute; top: 1534px; left: 78px; white-space: nowrap;" class="ft13"><b>MACSON</b></p>
            <p style="position: absolute; top: 1534px; left: 266px; white-space: nowrap;" class="ft14"><b>15%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 1578px; left: 71px; white-space: nowrap;" class="ft13"><b>MAS&#160;FOOD&#160;</b></p>
            <p style="position: absolute; top: 1595px; left: 82px; white-space: nowrap;" class="ft13"><b>LOVERS</b></p>
            <p style="position: absolute; top: 1576px; left: 206px; white-space: nowrap;" class="ft14"><b>Regalo&#160;por&#160;compra&#160;superior&#160;a&#160;60€,&#160;lata&#160;de</b></p>
            <p style="position: absolute; top: 1593px; left: 204px; white-space: nowrap;" class="ft14"><b>aceite&#160;de&#160;oliva&#160;extra&#160;virgen&#160;100%&#160;arbequina</b></p>
            <p style="position: absolute; top: 1646px; left: 88px; white-space: nowrap;" class="ft13"><b>MUJI</b></p>
            <p style="position: absolute; top: 1636px; left: 206px; white-space: nowrap;" class="ft14"><b>Regalo&#160;por&#160;compra&#160;superior&#160;a&#160;50€,&#160;de&#160;una</b></p>
            <p style="position: absolute; top: 1653px; left: 257px; white-space: nowrap;" class="ft14"><b>bolsa&#160;algodón&#160;orgánico</b></p>
            <p style="position: absolute; top: 1699px; left: 70px; white-space: nowrap;" class="ft13"><b>NORTH&#160;SAILS</b></p>
            <p style="position: absolute; top: 1693px; left: 219px; white-space: nowrap;" class="ft14"><b>20%&#160;de&#160;descuento&#160;por&#160;la&#160;compra&#160;de&#160;3</b></p>
            <p style="position: absolute; top: 1710px; left: 293px; white-space: nowrap;" class="ft14"><b>artículos</b></p>
            <p style="position: absolute; top: 1748px; left: 91px; white-space: nowrap;" class="ft13"><b>PLAY</b></p>
            <p style="position: absolute; top: 1748px; left: 265px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 1800px; left: 83px; white-space: nowrap;" class="ft13"><b>RITUALS</b></p>
            <p style="position: absolute; top: 1784px; left: 204px; white-space: nowrap;" class="ft14"><b>15%&#160;de&#160;descuento&#160;por&#160;compra&#160;superiores&#160;a</b></p>
            <p style="position: absolute; top: 1802px; left: 204px; white-space: nowrap;" class="ft14"><b>45€&#160;(no&#160;acumulable&#160;a&#160;otras&#160;ofertas,&#160;ni&#160;por</b></p>
            <p style="position: absolute; top: 1819px; left: 242px; white-space: nowrap;" class="ft14"><b>la&#160;compra&#160;de&#160;tarjeta&#160;regalo)</b></p>
            <p style="position: absolute; top: 1855px; left: 75px; white-space: nowrap;" class="ft13"><b>SCALPERS</b></p>
            <p style="position: absolute; top: 1873px; left: 87px; white-space: nowrap;" class="ft13"><b>HOME</b></p>
            <p style="position: absolute; top: 1858px; left: 214px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento&#160;por&#160;compra&#160;superior&#160;a</b></p>
            <p style="position: absolute; top: 1876px; left: 303px; white-space: nowrap;" class="ft14"><b>150€</b></p>
            <p style="position: absolute; top: 1912px; left: 89px; white-space: nowrap;" class="ft13"><b>SILVIA</b></p>
            <p style="position: absolute; top: 1930px; left: 53px; white-space: nowrap;" class="ft13"><b>SENNACHERRIBO</b></p>
            <p style="position: absolute; top: 1923px; left: 269px; white-space: nowrap;" class="ft14"><b>5%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 1968px; left: 82px; white-space: nowrap;" class="ft13"><b>SIMORRA</b></p>
            <p style="position: absolute; top: 1970px; left: 265px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 2007px; left: 62px; white-space: nowrap;" class="ft13"><b>SYSTEM&#160;ACTION</b></p>
            <p style="position: absolute; top: 2008px; left: 266px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento</b></p>
            <p style="position: absolute; top: 2045px; left: 77px; white-space: nowrap;" class="ft13"><b>TEA&#160;SHOP</b></p>
            <p style="position: absolute; top: 2088px; left: 55px; white-space: nowrap;" class="ft13"><b>THOMAS&#160;SHABO</b></p>
            <p style="position: absolute; top: 2082px; left: 227px; white-space: nowrap;" class="ft14"><b>10%&#160;de&#160;descuento&#160;excepto&#160;ofertas&#160;y</b></p>
            <p style="position: absolute; top: 2099px; left: 286px; white-space: nowrap;" class="ft14"><b>promociones</b></p>
            <p style="position: absolute; top: 2149px; left: 233px; white-space: nowrap;" class="ft15"><b>&#160;</b></p>
            <p style="position: absolute; top: 2141px; left: 86px; white-space: nowrap;" class="ft16">*Beneficios&#160;no&#160;acumulables&#160;con&#160;otras&#160;ofertas&#160;o&#160;promociones.&#160;</p>
            <p style="position: absolute; top: 2157px; left: 86px; white-space: nowrap;" class="ft16">**Beneficios&#160;válidos&#160;únicamente&#160;en&#160;tiendas&#160;de&#160;L’illa&#160;Diagonal.</p>
            <p style="position: absolute; top: 2047px; left: 255px; white-space: nowrap;" class="ft14"><b>Un&#160;té&#160;para&#160;llevar&#160;gratis</b></p>
        </div>
    </body>
</html>

                                        </code>
                                    </textarea>
                                </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Save changes</button>
                        </div>

                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    @include('pages.dashboard.ckeditor')
@endsection
