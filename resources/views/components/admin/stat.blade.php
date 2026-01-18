@props(['title' => 'العنوان'])
<div class="bg-white rounded-2xl shadow-sm border p-4">
  <h3 class="text-base font-semibold mb-3">{{ $title }}</h3>
  <div>{{ $slot }}</div>
</div>

أسماء الروابط المستخدمة

تأكد إن عندك هذه المسارات (أو عادلها حسب ملفاتك الحالية):

admin.import.index → Dashboard\ImportController@index

admin.export.index → Dashboard\ExportController@index

admin.manufacturing.index → Dashboard\ManufacturingController@index

admin.clearance.index → Dashboard\CustomsClearanceController@index

admin.shipping.index → Dashboard\ContainersController@index (أو وحدة ال��حن لديك)

admin.agent.index → Dashboard\AgentController@index

الخدمات:

admin.tariff.index → Dashboard\TariffController@index

admin.customs-users.index → Dashboard\CustomsUserController@index

admin.expat-cars.index → Dashboard\ExpatCarsController@index

admin.containers.index → Dashboard\ContainersController@index

admin.articles.index → Dashboard\ArticlesController@index

admin.ads.index → Dashboard\AdsController@index

admin.api-console.index → Dashboard\ApiConsoleController@index

لو اسم Route مختلف عندك، فقط بدّل اسم route() في الروابط.
