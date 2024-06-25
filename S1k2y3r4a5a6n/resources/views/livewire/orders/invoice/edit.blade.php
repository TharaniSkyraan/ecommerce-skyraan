
<div>
    <div class="float-end mb-2"> <a class="primary1" href="javascript:void(0);" wire:click="InvoiceDownload"><p>Download Invoice <i class="bx bx-download"></i> </p></a> </div>
    <iframe frameborder="0"  style="overflow:hidden;height:71rem;width:100%;text-align: -webkit-center !important;" src="data:application/pdf;base64,{{ $pdfBase64 }}#zoom=100&toolbar=0&navpanes=0&scrollbar=0&align=center" type="application/pdf" width="100%" height="600px"></iframe>
</div>