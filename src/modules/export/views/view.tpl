{if Yii::$app->user->identity->isPermission('viewExport')}
	<div class="row">
		<div class="col-sm-12 margin-bottom">
			{if Yii::$app->user->identity->isPermission('editExport')}
				{Html::a(Html::fa('pencil'),Url::toRoute(['export/edit', 'id' => $export->id]), [ 'class' => 'btn btn-success', 'title' => $export->title ])}
			{/if}
			{if Yii::$app->user->identity->isPermission('generateExport')}
				{Html::a(Yii::tr('Generate', [], 'export'),Url::toRoute(['export/generate', 'id' => $export->id]), [ 'class' => 'btn btn-info', 'title' => $export->title ])}
			{/if}
		</div>
		<div class="col-sm-12">
			<dl>
				<dt>{Yii::tr('ID', [], 'export')}</dt>
				<dd>{$export->id}</dd>
				<dt>{Yii::tr('Title', [], 'export')}</dt>
				<dd>{$export->title}</dd>
				<dt>{Yii::tr('Description', [], 'export')}</dt>
				<dd>{$export->description}</dd>
				<dt>{Yii::tr('Query', [], 'export')}</dt>
				<dd>
					<pre>{$export->query}</pre>
				</dd>
				<dt>{Yii::tr('Prefix', [], 'export')}</dt>
				<dd>{$export->prefix}</dd>
				<dt>{Yii::tr('CreateDT', [], 'export')}</dt>
				<dd>{Yii::$app->formatter->asDateTime($export->createDT)}</dd>
				<dt>{Yii::tr('Dt', [], 'export')}</dt>
				<dd>{Yii::$app->formatter->asDateTime($export->dt)}</dd>
			</dl>
		</div>
		{if isset($models) and count($models)>0}
			<table class="table table-bordered table-hover" id="export-list">
				<thead>
					<tr>
						{foreach  array_keys(current($models)) as $key}
							<th>
								{Yii::tr(ucfirst($key))}
							</th>
						{/foreach}
					</tr>
				</thead>
				<tbody>
					{foreach $models as $k=>$model}
						<tr>
							{foreach $model as $item}
							<td>{$item}</td>
							{/foreach}
						</tr>
					{/foreach}
				</tbody>
			</table>
			{include file='pagination.tpl'}
		{/if}
	</div>
{else}
	<div class="text-danger">{Yii::tr('Попытка взлома detected!')}</div>
{/if}